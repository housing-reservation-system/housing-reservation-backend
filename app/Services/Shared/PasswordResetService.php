<?php

namespace App\Services\Shared;

use App\Models\User;
use Illuminate\Support\Str;
use App\Mail\PasswordResetCodeMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class PasswordResetService
{
    public function sendResetCode(string $email): void
    {
        $user = User::where('email', $email)->first();
        if (!$user) return;

        $code = (string) rand(100000, 999999);
        $user->email_verification_code = $code;
        $user->email_verification_code_expires_at = now()->addMinutes(config('auth.passwords.users.expire', 60));
        $user->save();

        Mail::to($email)->send(new PasswordResetCodeMail($code));
    }

    public function confirmResetCode(string $email, string $code, string $password): User
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['Invalid email. Please try again.'],
            ]);
        }

        if ($user->email_verification_code !== $code) {
            throw ValidationException::withMessages([
                'code' => ['Invalid code. Please try again.']
            ]);
        }

        $user->forceFill([
            'password' => Hash::make($password),
            'email_verification_code' => null,
        ])->setRememberToken(Str::random(60));

        $user->save();

        return $user;
    }
}
