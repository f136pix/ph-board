<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\OrderController;
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

// register user
Route::post('register', [AuthController::class, 'register']);

// auth user
Route::post('login', [AuthController::class, 'authenticate']);

// protected routes
Route::group(['middleware' => 'auth:sanctum'],function () {
    // auth resources
    Route::get('user', [AuthController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::put('users/info', [AuthController::class, 'updateInfo']);
    Route::put('users/password', [AuthController::class, 'updatePassword']);


    // user resources
    /*
    Route::get('users', [UserController::class], 'index');
    Route::post('users', [UserController::class], 'store');
    Route::get('users/{id}', [UserController::class], 'show');
    Route::put('users/{id}', [UserController::class], 'update');
    Route::delete('users/{id}', [UserController::class], 'destroy');*/
    Route::apiResource('users', UserController::class);
    // role resources
    Route::apiResource('roles', RoleController::class);
    // products resources
    Route::apiResource('products', ProductController::class);
    // permission resource
    Route::get('permissions',[PermissionController::class, 'index']);
    // upload image
    Route::post('upload',[ImageController::class, 'upload']);
    // orders
    Route::apiResource('orders', OrderController::class)->only('index', 'show');
    // export csv
    Route::post('export',[OrderController::class, 'export']);
    // chart data
    Route::get('chart',[OrderController::class,'chart']);
});
