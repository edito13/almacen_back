<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExitController;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\EquipmentController;

Route::get('/ping', function () {
    return response()->json(['message' => 'API está viva!']);
});

Route::get('/', function (Request $request) {
    return response()->json([
        'message' => 'Hello World'
    ]);
});

Route::post('/auth/login',    [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    //Resources
    Route::apiResource('exit', ExitController::class);
    Route::apiResource('entry', EntryController::class);
    Route::apiResource('category', CategoryController::class);
    Route::apiResource('equipment', EquipmentController::class);

    // movement
    Route::get('/movement', [MovementController::class, 'index']);

    // stock
    Route::get('/stock', [StockController::class, 'index']);

    // auth
    Route::post('/logout', [AuthController::class, 'logout']);

    // user
    Route::get('/user', fn (Request $request) => $request->user());
});
