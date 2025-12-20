<?php

use App\Http\Controllers\Shared\ApartmentController;
use Illuminate\Support\Facades\Route;


Route::post('apartments',[ApartmentController::class,'store'])->middleware('auth:api');
Route::get('apartments',[ApartmentController::class,'index']);
Route::get('apartments/search',[ApartmentController::class,'search']);
Route::get('apartments/{apartment}',[ApartmentController::class,'show']);
