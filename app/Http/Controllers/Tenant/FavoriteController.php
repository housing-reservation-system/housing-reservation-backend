<?php

namespace App\Http\Controllers\Tenant;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApartmentTenantListResource;

class FavoriteController extends Controller
{
    use ApiResponse;
    public function toggle($apartmentId, Request $request)
    {
        $result = $request->user()->favorites()->toggle($apartmentId);
        return $this->successMessage(
            empty($result['attached'])
                ? 'The apartment has been removed from your favorites.'
                : 'The apartment has been added to your favorites.'
        );
    }

    public function index(Request $request)
    {
        $favorites = $request->user()->favorites;
        return $this->success(
            ApartmentTenantListResource::collection($favorites),
            'Favorites retrieved successfully'
        );
    }
}
