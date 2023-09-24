<?php

namespace App\Http\Controllers;

use App\Models\subscription;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;

use App\Models\User;

class TransactionController extends Controller
{
    public function store(): JsonResponse
    {
        try {
            $subscription = Subscription::where(
                ['user_id' => request('id'), 'id' => request('subscription_id')])->first();

            if(!$subscription) {
                return response()->json([
                    'code' => 404,
                    'message' => 'No subscription found',
                ], 404);
            }

            Transaction::create([
                'subscription_id' => request('subscription_id'),
                'price' => request('price'),
            ]);

            return response()->json([
                'code' => 201,
                'message' => 'Successfully created transaction',
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => 'Failed to create transaction',
                'error' => $th->getMessage(),
            ], 500);
        }
    }
}
