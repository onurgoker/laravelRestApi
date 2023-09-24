<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::post('/{id}/subscription', [SubscriptionController::class, 'store']); //done
    Route::put('/{id}/subscription/{user_id}', [SubscriptionController::class, 'update']); //done
    Route::delete('/{id}/subscription   ', [SubscriptionController::class, 'delete']); //done
    Route::post('/{id}/transaction', [TransactionController::class, 'store']); //done
    Route::get('/{id}', [SubscriptionController::class, 'get']); //done
});
