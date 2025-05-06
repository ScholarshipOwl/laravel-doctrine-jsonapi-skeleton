<?php

namespace App\Sanctum;

use App\Sanctum\Contract\ApiTokenContract;
use App\Sanctum\Entities\PersonalAccessToken;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Str;
use DateTimeInterface;

trait WithApiTokens
{
    /**
     * The access token the user is using for the current request.
     */
    protected ?ApiTokenContract $accessToken = null;

    /**
     * The collection of access tokens associated with this user.
     * This will be initialized in the constructor of the class using this trait.
     *
     * @var Collection|ApiTokenContract[]
     */
    #[ORM\OneToMany(
        mappedBy: "user",
        targetEntity: PersonalAccessToken::class,
        cascade: ["persist"],
        orphanRemoval: true
    )]
    protected Collection $tokens;

    /**
     * Get the access tokens that belong to the model.
     *
     * @return Collection|ApiTokenContract[]
     */
    public function tokens(): Collection
    {
        return $this->tokens;
    }

    /**
     * Determine if the current API token has a given scope.
     */
    public function tokenCan(string $ability): bool
    {
        return !is_null($this->accessToken) && $this->accessToken->can($ability);
    }

    /**
     * Determine if the current API token does not have a given scope.
     */
    public function tokenCant(string $ability): bool
    {
        return !$this->tokenCan($ability);
    }

    /**
     * Create a new personal access token for the user.
     */
    public function createToken(
        string $name,
        array $abilities = ['*'],
        ?DateTimeInterface $expiresAt = null
    ): NewAccessToken {
        $plainTextToken = $this->generateTokenString();

        $token = new PersonalAccessToken();
        $token->setName($name)
            ->setToken(hash('sha256', $plainTextToken))
            ->setAbilities($abilities)
            ->setExpiresAt($expiresAt)
            ->setUser($this);

        $this->tokens()->add($token); // Add to collection

        $em = app(EntityManager::class);
        $em->persist($token);
        $em->flush();

        return new NewAccessToken($token, $token->getId() . '|' . $plainTextToken);
    }

    /**
     * Generate the token string.
     */
    public function generateTokenString(): string
    {
        return sprintf(
            '%s%s%s',
            config('sanctum.token_prefix', ''),
            $tokenEntropy = Str::random(40),
            hash('crc32b', $tokenEntropy)
        );
    }

    /**
     * Get the access token currently associated with the user.
     */
    public function currentAccessToken(): ?ApiTokenContract
    {
        return $this->accessToken;
    }

    /**
     * Set the current access token for the user.
     *
     * @return static
     */
    public function withAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }
}
