<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApartmentTenantFullResource;
use App\Http\Resources\ApartmentTenantListResource;
use App\Models\Apartment;
use App\Services\ApartmentTenantService;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApartmentController extends Controller
{
    use ApiResponse;
    protected $service;

    public function __construct(ApartmentTenantService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        try {
            $apartments = $this->service->index();
            return $this->success(
             ApartmentTenantListResource::collection($apartments),
                'Apartments retrieved successfully',
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return $this->error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Apartment $apartment)
    {
        try {
            $apartment = $this->service->show($apartment);
            return $this->success(
                new ApartmentTenantFullResource($apartment),
                'Apartment details retrieved successfully',
                Response::HTTP_OK
            );
        } catch (Exception $e) {
            return $this->error($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
    public function filter(Request $request)
{
    try {
        $apartments = $this->service->filter($request);
        return $this->success(
            ApartmentTenantListResource::collection($apartments),
            'Apartments filtered successfully',
            Response::HTTP_OK
        );
    } catch (Exception $e) {
        return $this->error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
}

