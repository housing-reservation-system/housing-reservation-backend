<?php

namespace App\Http\Controllers\Shared;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use App\Services\Shared\PasswordResetService;
use App\Http\Requests\SendResetLinkRequest;
use App\Http\Requests\ResetPasswordRequest;

class PasswordResetController extends Controller
{
    use ApiResponse;

    protected $passwordResetService;

    public function __construct(PasswordResetService $passwordResetService)
    {
        $this->passwordResetService = $passwordResetService;
    }

    public function sendResetLink(SendResetLinkRequest $request)
    {
        $email = $request->validated()['email'];

        try {
            $user = User::where('email', $email)->first();
            if ($user && !$user->hasVerifiedEmail()) {
                return $this->error(
                    'Your account is not verified. Please verify your email to proceed.',
                    Response::HTTP_FORBIDDEN
                );
            }

            $this->passwordResetService->sendResetCode($email);

            return $this->successMessage(
                'If an account with that email exists, a password reset link has been sent to your email address.',
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->error(
                'Failed to send password reset link. Please try again later.',
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $this->passwordResetService->confirmResetCode(
                $request->email,
                $request->code,
                $request->password
            );

            return $this->successMessage(
                'Password reset successfully',
                Response::HTTP_OK
            );
        } catch (ValidationException $e) {
            return $this->error(
                $e->getMessage(),
                Response::HTTP_BAD_REQUEST
            );
        } catch (\Exception $e) {
            return $this->error(
                $e->getMessage(),
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
