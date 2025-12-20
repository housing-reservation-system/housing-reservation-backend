<?php

namespace App\Http\Controllers\Host;

use App\Http\Requests\ApartmentRequest;
use App\Http\Requests\UpdateApartmentRequest;
use App\Http\Resources\ApartmentResource;
use App\Models\Apartment;
use App\Services\ApartmentService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ApartmentController extends Controller
{
    use ApiResponse,AuthorizesRequests;

    protected $apartmentService;

    public function __construct(ApartmentService $apartmentService)
    {
        $this->apartmentService = $apartmentService;
    }

    public function index()
    {
        try {
            $apartments = $this->apartmentService->getUserApartments(Auth::id());
            return $this->success(
                ApartmentResource::collection($apartments),
                'Apartments retrieved successfully',
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(ApartmentRequest $request)
    {
        try {
            $apartment = $this->apartmentService->createApartment(
                $request->validated(),
                Auth::id()
            );
            
            return $this->success(
                new ApartmentResource($apartment),
                'Apartment created successfully',
                Response::HTTP_CREATED
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Apartment $apartment)
    {
        try {            
            $this->authorize('view', $apartment);
            
            $apartment->load(['location' => function ($q) {
                $q->selectRaw('*, ST_X(point) as longitude, ST_Y(point) as latitude');
            }]);

            return $this->success(
                new ApartmentResource($apartment),
                'Apartment retrieved successfully',
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR;
            return $this->error($e->getMessage(), $statusCode);
        }
    }

    public function update(UpdateApartmentRequest $request, Apartment $apartment)
    {
        try {
            $this->authorize('update', $apartment);
            
            $updatedApartment = $this->apartmentService->updateApartment(
                $apartment,
                $request->validated()
            );

            return $this->success(
                new ApartmentResource($updatedApartment),
                'Apartment updated successfully',
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR;
            return $this->error($e->getMessage(), $statusCode);
        }
    }

    public function destroy(Apartment $apartment)
    {
        try {
            $this->authorize('delete', $apartment);
            
            $this->apartmentService->deleteApartment($apartment);

            return $this->successMessage(
                'Apartment deleted successfully',
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR;
            return $this->error($e->getMessage(), $statusCode);
        }
    }

    public function updateImages(Request $request, Apartment $apartment)
    {
        try {
            $this->authorize('update', $apartment);

            $request->validate([
                'main_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg', 'max:2048'],
                'images' => ['nullable', 'array', 'max:10'],
                'images.*' => ['image', 'mimes:jpeg,png,jpg,svg', 'max:2048'],
            ]);

            $updatedApartment = $this->apartmentService->updateApartmentImages(
                $apartment,
                $request->file('main_image'),
                $request->file('images')
            );

            return $this->success(
                new ApartmentResource($updatedApartment),
                'Apartment images updated successfully',
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            $statusCode = $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR;
            return $this->error($e->getMessage(), $statusCode);
        }
    }
}
