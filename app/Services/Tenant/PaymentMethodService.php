<?php

namespace App\Services\Tenant;

use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;

class PaymentMethodService
{
    public function getUserPaymentMethods(int $userId)
    {
        return PaymentMethod::where('user_id', $userId)->get();
    }

    public function createPaymentMethod(array $data, int $userId)
    {
        return DB::transaction(function () use ($data, $userId) {
            if (!empty($data['is_default']) && $data['is_default']) {
                $this->resetDefaultPaymentMethod($userId);
            }

            return PaymentMethod::create(array_merge($data, ['user_id' => $userId]));
        });
    }

    public function updatePaymentMethod(PaymentMethod $paymentMethod, array $data)
    {
        return DB::transaction(function () use ($paymentMethod, $data) {
            $userId = $paymentMethod->user_id;

            if (!empty($data['is_default']) && $data['is_default']) {
                $this->resetDefaultPaymentMethod($userId);
            }

            $hasBookings = $paymentMethod->bookings()->exists();

            if ($hasBookings) {
                $paymentMethod->delete();

                $newData = array_merge([
                    'user_id' => $userId,
                    'card_brand' => $paymentMethod->card_brand,
                    'last_four_digits' => $paymentMethod->last_four_digits,
                    'card_holder_name' => $paymentMethod->card_holder_name,
                    'expiry_date' => $paymentMethod->expiry_date,
                    'is_default' => $paymentMethod->is_default,
                ], $data);

                return PaymentMethod::create($newData);
            }

            $paymentMethod->update($data);
            return $paymentMethod->fresh();
        });
    }

    public function deletePaymentMethod(PaymentMethod $paymentMethod)
    {
        return $paymentMethod->delete();
    }

    private function resetDefaultPaymentMethod(int $userId)
    {
        PaymentMethod::where('user_id', $userId)
            ->where('is_default', true)
            ->update(['is_default' => false]);
    }
}
