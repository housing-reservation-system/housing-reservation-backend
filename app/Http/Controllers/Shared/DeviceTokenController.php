<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeviceTokenRequest;
use App\Services\Shared\DeviceTokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeviceTokenController extends Controller
{
    protected DeviceTokenService $deviceTokenService;

    public function __construct(DeviceTokenService $deviceToken)
    {
        $this->deviceTokenService = $deviceToken;
    }

    public function store(DeviceTokenRequest $request)
    {
        $this->deviceTokenService->storeToken(
            Auth::id(),
            $request->validated()
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Device token saved successfully.',
        ]);
    }

    public function destroy(Request $request)
    {
        $request->validate(['token' => 'required|string']);

        $this->deviceTokenService->deactivateToken(
            Auth::id(),
            $request->token
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Device token deactivated.',
        ]);
    }
}
