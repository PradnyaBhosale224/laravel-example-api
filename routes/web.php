<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FormController;


Route::get('/', function () {
    return view('welcome');
});

// Route::middleware('auth:sanctum')->group(function () {
    Route::post('/create-form', [FormController::class, 'createForm']);
// });
// Route::apiResource('create-form', FormController::class);