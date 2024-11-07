<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\StudentFormController;
use App\Http\Controllers\ImgUploadController;
use App\Http\Controllers\CSVImportController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\AdminController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/test', function (Request $request) {
    return response()->json('hello');
});

Route::get('/all-posts', [PostController::class, 'index']);
Route::post('/create-posts', [PostController::class, 'createPost']);
Route::get('/get-single-post/{post}', [PostController::class, 'show']);
Route::post('/update-post/{post}', [PostController::class, 'update']);
Route::post('/delete-post/{post}', [PostController::class, 'destroy']);

//form controllers
Route::post('/create-form', [StudentFormController::class, 'createForm']);
Route::post('/update-form', [StudentFormController::class, 'updateForm']);
Route::post('/fetch-form', [StudentFormController::class, 'fetchForm']);

//upload controllers
Route::post('/upload-image', [ImgUploadController::class, 'uploadImage']); //api to store the img in folder
Route::post('/update-image', [ImgUploadController::class, 'updateImage']);

Route::post('/update-file-s3', [ImgUploadController::class, 'uploadFileS3']);
Route::post('/fetch-file-s3', [ImgUploadController::class, 'fetchFileS3']);

//importcsv
Route::post('/import-csv', [CSVImportController::class, 'importCSV']);

//exportcsv
Route::get('/export-csv', [ExportController::class, 'exportProducts']);
Route::post('/export-product', [ExportController::class, 'exportProductById']);

//complex query controller
Route::post('/conversation', [ConversationController::class, 'fetchConversation']);

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
});


Route::middleware(['auth:sanctum'])->get('/admin-dashboard', [AdminController::class, 'adminDashboard']);
