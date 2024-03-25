<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TimesheetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/user' , function(Request $request) {
        return $request->user();
    });

    Route::prefix('/project')->group(function() {
        Route::get('/', [ProjectController::class , 'index']);
        Route::post('/create' , [ProjectController::class , 'store']);
        Route::get('/{id}' ,[ProjectController::class , 'show']);
        Route::patch('/{id}/update', [ProjectController::class , 'update']);
        Route::delete('/{id}/delete' , [ProjectController::class , 'destroy']);
    });

    Route::prefix('/timesheet')->group(function() {
        Route::get('/', [TimesheetController::class , 'index']);
        Route::post('/create' , [TimesheetController::class , 'store']);
        Route::get('/{id}' ,[TimesheetController::class , 'show']);
        Route::patch('/{id}/update', [TimesheetController::class , 'update']);
        Route::delete('/{id}/delete' , [TimesheetController::class , 'destroy']);
    });

});


require __DIR__.'/auth.php';

