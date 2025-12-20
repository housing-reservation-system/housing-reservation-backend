<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreApartmentRequest;
use App\Http\Resources\ApartmentDetailsResource;
use App\Http\Resources\ApartmentResource;
use App\Models\Apartment;
use App\Services\Shared\ApartmentServices;
use Illuminate\Http\Request;

class ApartmentController extends Controller
{
  protected $apartmentServices;
  public function __construct( $apartmentServices)
  {
  }
  public function index(){
    $apartments = $this->apartmentServices->getApartmentList();
    return new ApartmentDetailsResource($apartments);
  }
  public function search(Request $request){
    $apartments=$this->apartmentServices->searchApartments($request);
    return new ApartmentDetailsResource($apartments);
  }
public function show(Apartment $apartment){
    $apartmentDetails =$this->apartmentServices->getApartmentDetails($apartment);
    return new ApartmentResource($apartmentDetails);
}
public function store(StoreApartmentRequest $request){
    $apartment=$this->apartmentServices->createApartment($request);
    return new ApartmentResource($apartment);
}
}
