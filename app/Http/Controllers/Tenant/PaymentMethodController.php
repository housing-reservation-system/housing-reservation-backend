<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentMethodRequest;
use App\Http\Requests\UpdatePaymentMethodRequest;
use App\Http\Resources\PaymentMethodResource;
use App\Models\PaymentMethod;
use App\Services\Tenant\PaymentMethodService;
use App\Traits\ApiResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PaymentMethodController extends Controller
{
    use ApiResponse, AuthorizesRequests;

    protected $paymentMethodService;

    public function __construct(PaymentMethodService $paymentMethodService)
    {
        $this->paymentMethodService = $paymentMethodService;
    }

    public function index()
    {
        $methods = $this->paymentMethodService->getUserPaymentMethods(Auth::id());
        return $this->success(
            PaymentMethodResource::collection($methods),
            'Payment methods retrieved successfully'
        );
    }

    public function store(PaymentMethodRequest $request)
    {
        $method = $this->paymentMethodService->createPaymentMethod(
            $request->validated(),
            Auth::id()
        );

        return $this->success(
            new PaymentMethodResource($method),
            'Payment method added successfully',
            Response::HTTP_CREATED
        );
    }

    public function update(UpdatePaymentMethodRequest $request, PaymentMethod $paymentMethod)
    {
        $this->authorize('update', $paymentMethod);

        $newMethod = $this->paymentMethodService->updatePaymentMethod(
            $paymentMethod,
            $request->validated()
        );

        return $this->success(
            new PaymentMethodResource($newMethod),
            'Payment method updated successfully'
        );
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $this->authorize('delete', $paymentMethod);

        $this->paymentMethodService->deletePaymentMethod($paymentMethod);

        return $this->successMessage('Payment method removed successfully');
    }
}
