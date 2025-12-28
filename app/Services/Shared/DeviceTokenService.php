<?php

namespace App\Services\Shared;

use App\Models\DeviceToken;

class DeviceTokenService
{
    public function storeToken(int $userId, array $data): DeviceToken
    {
        return DeviceToken::updateOrCreate(
            ['user_id' => $userId, 'device_id' => $data['device_id'] ?? null],
            [
                'token' => $data['token'],
                'platform' => $data['platform'],
                'is_active' => true,
                'last_used_at' => now(),
            ]
        );
    }

    public function deactivateToken(int $userId, string $token): bool
    {
        return DeviceToken::where('user_id', $userId)
            ->where('token', $token)
            ->update(['is_active' => false]) > 0;
    }
}
