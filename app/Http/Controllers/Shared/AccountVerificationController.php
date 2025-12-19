<?php

namespace App\Http\Controllers\Shared;

use App\Traits\ApiResponse;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\VerifyAccountRequest;
use App\Services\Shared\AccountVerificationService;

class AccountVerificationController extends Controller
{
    use ApiResponse;

    protected $verificationService;

    public function __construct(AccountVerificationService $verificationService)
    {
        $this->verificationService = $verificationService;
    }

    public function verifyAccount(VerifyAccountRequest $request)
    {
        try {
            $user = $this->verificationService->verifyAccount($request);
            
            return $this->successMessage(
                'Account verification submitted successfully. Your account is now pending approval.',
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR;
            return $this->error($e->getMessage(), $statusCode);
        }
    }

    public function getVerificationStatus()
    {
        try {
            $status = $this->verificationService->getVerificationStatus();
            
            return $this->success(
                $status,
                'Verification status retrieved successfully',
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR;
            return $this->error($e->getMessage(), $statusCode);
        }
    }
}
