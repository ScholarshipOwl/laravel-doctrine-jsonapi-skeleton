<?php

namespace App\Sanctum;

use App\Sanctum\Contract\ApiTokenContract;
use App\Sanctum\Events\TokenAuthenticated;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Laravel\Sanctum\Contracts\HasApiTokens;
use Laravel\Sanctum\Sanctum;

class Guard
{
    public function __construct(
        protected AuthFactory $auth,
        protected $expiration = null,
        protected $provider = null
    ) {
    }

    /**
     * Retrieve the authenticated user for the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function __invoke(Request $request): HasApiTokens|null
    {
        foreach (Arr::wrap(config('sanctum.guard', 'web')) as $guard) {
            if ($user = $this->auth->guard($guard)->user()) {
                return $user instanceof HasApiTokens
                    ? $user->withAccessToken(new TransientToken($user))
                    : $user;
            }
        }

        if ($token = $this->getTokenFromRequest($request)) {
            $accessToken = $this->findToken($token);

            if (
                ! $this->isValidAccessToken($accessToken) ||
                ! $accessToken->getTokenable() instanceof HasApiTokens
            ) {
                return null;
            }

            $tokenable = $accessToken->getTokenable()->withAccessToken(
                $accessToken
            );

            event(new TokenAuthenticated($accessToken));

            $accessToken->setLastUsedAt(now()->toDateTimeImmutable());
            app(EntityManager::class)->flush();

            return $tokenable;
        }

        return null;
    }

    protected function supportsTokens(mixed $user = null): bool
    {
        return $user instanceof HasApiTokens;
    }

    /**
     * Get the token from the request.
     */
    protected function getTokenFromRequest(Request $request): ?string
    {
        if (is_callable(Sanctum::$accessTokenRetrievalCallback)) {
            return (string) (Sanctum::$accessTokenRetrievalCallback)($request);
        }

        $token = $request->bearerToken();

        return $this->isValidBearerToken($token) ? $token : null;
    }

    /**
     * Determine if the bearer token is in the correct format.
     */
    protected function isValidBearerToken(?string $token = null): bool
    {
        if (! is_null($token) && str_contains($token, '|')) {
            $idType = app(EntityManager::class)
                ->getClassMetadata(Sanctum::$personalAccessTokenModel)
                ->getSingleIdReflectionProperty()
                ->getType();

            if ($idType->isBuiltin() && $idType->getName() === 'int') {
                [$id, $token] = explode('|', $token, 2);

                return ctype_digit($id) && ! empty($token);
            }
        }

        return ! empty($token);
    }

    protected function isValidAccessToken(?ApiTokenContract $accessToken): bool
    {
        if (! $accessToken) {
            return false;
        }

        $isValid =
            (! $this->expiration || now()->subMinutes($this->expiration)->lt($accessToken->getCreatedAt()))
            && (! $accessToken->getExpiresAt() || ! Carbon::instance($accessToken->getExpiresAt())->isPast())
            && $this->hasValidProvider($accessToken->getTokenable());

        if (is_callable(Sanctum::$accessTokenAuthenticationCallback)) {
            $isValid = (bool) (Sanctum::$accessTokenAuthenticationCallback)($accessToken, $isValid);
        }

        return $isValid;
    }

    protected function hasValidProvider(HasApiTokens $tokenable): bool
    {
        if ($this->provider === null) {
            return true;
        }

        $model = config("auth.providers.{$this->provider}.model");

        return $tokenable instanceof $model;
    }

    public static function findToken($token): null|ApiTokenContract
    {
        $em = app(EntityManager::class);
        /** @var class-string<ApiTokenContract> $entityClass */
        $entityClass = Sanctum::$personalAccessTokenModel;

        if (strpos($token, '|') === false) {
            return $em->getRepository($entityClass)
                ->findOneBy(['token' => hash('sha256', $token)]);
        }

        [$id, $token] = explode('|', $token, 2);

        if ($instance = $em->find($entityClass, $id)) {
            // Compare the already-hashed token from DB ($instance->getToken()) with the hashed input token part.
            return hash_equals($instance->getToken(), hash('sha256', $token)) ? $instance : null;
        }

        return null;
    }
}
