<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApartmentTenantFullResource;
use App\Http\Resources\ApartmentTenantListResource;

use App\Models\Apartment;
use App\Services\ApartmentTenantService;
use App\Services\FavoriteService;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApartmentController extends Controller
{
    use ApiResponse;
    protected $service;
protected $favoriteService;
    public function __construct(ApartmentTenantService $service ,FavoriteService $favoriteService)
    {
        $this->service = $service;
        $this->favoriteService=$favoriteService;
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

            $user=Auth::user();
            $isFavorited=false;
            if($user){
                $isFavorited=$this->favoriteService->isFavorited($user,$apartment);
            }
            $apartment = $this->service->show($apartment);
            $apartment->is_favorited=$isFavorited;
            return $this->success(['data'=>new ApartmentTenantFullResource($apartment),
            'is_favorited'=>$isFavorited,
            'message'=>'Apartment details retrieved successfully'],
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

