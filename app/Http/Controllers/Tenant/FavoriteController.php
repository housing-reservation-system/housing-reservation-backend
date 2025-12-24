<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Services\FavoriteService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
   use ApiResponse;
   public function __construct(protected FavoriteService $service){

   }
public function toggle($apartmentId,Request $request){
    $result=$this->service->toggleFavorite($request->user(),$apartmentId);
    return $this->success($result,
    $result['action']=='added'?'Added':'Removed'
);
}

}
