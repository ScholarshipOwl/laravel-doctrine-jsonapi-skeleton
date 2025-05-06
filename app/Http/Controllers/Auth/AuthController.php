<?php

namespace App\Http\Controllers\Auth;

use App\Entities\User;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response;

#[Group('Auth')]
class AuthController
{
    #[Endpoint('Login')]
    #[Response(['id' => 1], 200)]
    #[Response(null, 401)]
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            /** @var \App\Entities\User $user */
            $user = Auth::user();

            return response([
                'id' => $user->getId(),
            ]);
        }

        return response(null, 401);
    }

    #[Endpoint('Request password reset')]
    #[Response(['status' => 'Password reset link sent'], 200)]
    #[Response(['status' => 'Failed to send password reset link'], 400)]
    public function requestResetPassword(RequestResetPasswordRequest $request)
    {
        $email = $request->input('email');
        $status = Password::sendResetLink(['email' => $email]);

        if ($status === Password::RESET_LINK_SENT) {
            return response([
                'status' => __($status),
            ]);
        }

        return response([
            'status' => __($status),
        ], 400);
    }

    #[Endpoint('Reset password')]
    #[Response(['status' => 'Password reset'], 200)]
    #[Response(['status' => 'Failed to reset password'], 400)]
    public function resetPassword(ResetPasswordRequest $request)
    {
        $credentials = $request->only(['email', 'password', 'token']);

        $status = Password::reset(
            $credentials,
            function (User $user, $password) {
                $user->setPassword(Hash::make($password));
                Auth::logoutOtherDevices($password);
                Auth::logout();
                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response([
                'status' => __($status),
            ]);
        }

        return response([
            'status' => __($status),
        ], 400);
    }

    #[Endpoint('Logout')]
    #[Response(null, 204)]
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response(null, 204);
    }

    /**
     * Create API Token
     *
     * You can create a personal access token for a user.
     * You can use this token to authenticate requests to the API as Bearer token.
     */
    #[Response(['token' => 'string'], 200)]
    #[Response(['message' => 'Invalid credentials'], 401)]
    #[Response(['errors' => []], 422)]
    public function createToken(CreateTokenRequest $request)
    {
        $user = app(EntityManager::class)
            ->getRepository(User::class)
            ->findOneBy(['email' => $request->validated('email')]);

        if (! $user || ! Hash::check($request->validated('password'), $user->getPassword())) {
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        $expiresAtInput = $request->validated('expiresAt');
        $expiresAt = $expiresAtInput ? Carbon::parse($expiresAtInput)->toDateTimeImmutable() : null;

        $token = $user->createToken(
            $request->validated('deviceName'),
            $request->validated('abilities', ['*']),
            $expiresAt
        );

        return [
            'token' => $token->plainTextToken
        ];
    }
}
