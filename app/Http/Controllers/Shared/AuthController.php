<?php

namespace App\Http\Controllers\Shared;

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

class AuthController extends Controller
{
    use ApiResponse;

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        try {
            $this->authService->register($request);
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
        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR;
            return $this->error($e->getMessage(), $statusCode);
        }
    }


    public function verifyEmail(VerifyEmailRequest $request)
    {
        try {
            $this->authService->verifyEmail(
                $request->email,
                $request->code
            );

            return $this->successMessage("Email verified successfully.", Response::HTTP_OK);
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
