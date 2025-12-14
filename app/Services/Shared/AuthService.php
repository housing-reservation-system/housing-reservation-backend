<?php

namespace App\Services\Shared;

use App\Models\User;
use App\Mail\VerificationCodeMail;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthService
{
    public function register(Request $request): User
    {
        $code = $this->generateVerificationCode();

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone ?? null,
            'email' => $request->email ?? null,
            'password' => Hash::make($request->password),
            'date_of_birth' => $request->date_of_birth ?? null,
            'role' => $request->role,
            'email_verification_code' => $code,
        ]);

        if ($user->email) {
            $this->sendVerificationEmail($user, $code);
        }

        return $user;
    }

    public function login(Request $request): array
    {
        $credentials = $request->only(['email', 'password']);


        $token = JWTAuth::attempt($credentials);

        if (!$token) {
            throw new \Exception('Invalid credentials', 401);
        }

        $user = Auth::user();

        if ($user->email && !$user->email_verified_at) {
            throw new \Exception('Email not verified', 403);
        }

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function verifyEmail(string $email, string $code): bool
    {
        $user = User::where('email', $email)
            ->where('email_verification_code', $code)
            ->first();

        if (!$user) {
            throw new \Exception('Invalid verification code', 400);
        }

        if ($user->email_verified_at) {
            throw new \Exception('Email already verified', 400);
        }

        $user->email_verified_at = now();
        $user->email_verification_code = null;
        $user->save();

        return true;
    }

    public function resendVerificationCode(string $email): void
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            throw new \Exception('User not found', 404);
        }

        if ($user->email_verified_at) {
            throw new \Exception('Email already verified', 400);
        }

        $code = $this->generateVerificationCode();

        $user->email_verification_code = $code;
        $user->save();

        $this->sendVerificationEmail($user, $code);
    }

    public function updatePassword(Request $request): void
    {
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            throw new \Exception('Current password is incorrect', 403);
        }

        $user->password = Hash::make($request->password);
        $user->save();
    }

    public function logoutAndInvalidateJWT(): void
    {
        if (Auth::check()) {
            $token = JWTAuth::getToken();
            if ($token) {
                JWTAuth::invalidate($token);
                Auth::logout();
            }
        }
    }

    private function generateVerificationCode(): string
    {
        return (string) rand(100000, 999999);
    }

    private function sendVerificationEmail(User $user, string $code): void
    {
        $userName = $user->first_name . ' ' . $user->last_name;

        Mail::to($user->email)->send(
            new VerificationCodeMail($userName, $code)
        );
    }
}
