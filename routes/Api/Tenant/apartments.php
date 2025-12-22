<?php

use App\Http\Controllers\Tenant\ApartmentController;
use Illuminate\Support\Facades\Route;
Route::prefix('tenant')->name('tenant')->group(function(){
Route::get('apartments',[ApartmentController::class,'index']);
Route::get('apartments/{apartment}',[ApartmentController::class,'show']);
});
