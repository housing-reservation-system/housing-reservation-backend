<?php

namespace App\Http\Controllers\Shared;

use App\Enums\UserRole;
use App\Traits\ApiResponse;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\Shared\AuthService;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\ResendVerificationCodeRequest;
use App\Services\Shared\NotificationService;
use Illuminate\Container\Attributes\Log;

class AuthController extends Controller
{
    use ApiResponse;

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function registerTenant(RegisterRequest $request)
    {
        try {
           $user= $this->authService->register($request, UserRole::TENANT);
            app(NotificationService::class)->sendSuccessNotification(
                $user,
                'welcome ',
                'hello' . $user->first_name . 'thank you for joining us as a tenant.'
            );
            return $this->successMessage("User created successfully. Please check your email for verification code.", 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function registerHost(RegisterRequest $request)
    {
        try {
            $user=$this->authService->register($request, UserRole::HOST);
             app(NotificationService::class)->sendSuccessNotification(
                $user,
                'welcome ',
                'hello' . $user->first_name . 'thank you for joining us as a host.'
            );
            return $this->successMessage("User created successfully. Please check your email for verification code.", 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $result = $this->authService->login($request);
            $data = (new UserResource($result['user']))->toArray($request);

            return $this->success([
                ...$data,
                'token' => $result['token'],
            ], "Login successfully", Response::HTTP_OK);
            app(NotificationService::class)->sendInfoNotification(
$result['user'],
            ' Login successfully',
            'welcome back' . $result['user']->name . 'you have successfully logged in.'
            );
        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR;
            return $this->error($e->getMessage(), $statusCode);
        }
    }


    public function verifyEmail(VerifyEmailRequest $request)
    {
        try {
            $user = $this->authService->verifyEmail(
                $request->email,
                $request->code
            );

            $token = $this->authService->generateTokenForUser($user);
            $data = (new UserResource($user))->toArray($request);

            return $this->success([
                ...$data,
                'token' => $token,
            ], "Email verified and logged in successfully.", Response::HTTP_OK);
        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR;
            return $this->error($e->getMessage(), $statusCode);
        }
    }


    public function resendVerificationCode(ResendVerificationCodeRequest $request)
    {
        try {
            $this->authService->resendVerificationCode($request->email);

            return $this->successMessage("Verification code sent successfully.", Response::HTTP_OK);
        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR;
            return $this->error($e->getMessage(), $statusCode);
        }
    }

    public function logout()
    {
        try {
            $this->authService->logoutAndInvalidateJWT();
            return $this->successMessage('Logged out successfully', Response::HTTP_OK)
                ->withoutCookie('refresh_token')
                ->withoutCookie('role');
                  app(NotificationService::class)->sendInfoNotification(
              $user,
            ' Logout successfully',
            'you have been successfully logged out . see you soon ');
        } catch (\Exception $e) {
            return $this->error('Logout error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        try {
            $this->authService->updatePassword($request);
            $this->authService->logoutAndInvalidateJWT();

            return $this->successMessage("Password updated successfully.", Response::HTTP_OK);
        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR;
            return $this->error($e->getMessage(), $statusCode);
        }
    }
}
