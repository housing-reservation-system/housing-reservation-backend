<?php

namespace App\Http\Controllers\Shared;

use App\Traits\ApiResponse;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\Shared\ProfileService;
use App\Http\Requests\UpdateProfileRequest;

class ProfileController extends Controller
{
    use ApiResponse;

    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function show()
    {
        try {
            $user = $this->profileService->getProfile();
            
            return $this->success(
                new UserResource($user),
                'Profile retrieved successfully',
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR;
            return $this->error($e->getMessage(), $statusCode);
        }
    }

    public function update(UpdateProfileRequest $request)
    {
        try {
            $user = $this->profileService->updateProfile($request);
            
            return $this->success(
                new UserResource($user),
                'Profile updated successfully',
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR;
            return $this->error($e->getMessage(), $statusCode);
        }
    }
}
