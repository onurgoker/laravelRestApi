<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

use App\Models\User;

class TransactionController extends Controller
{
    public function store(): JsonResponse
    {
        try {
            $user = User::findOrFail(request('subscription_id'));

            if($user->isEmpty()) {
                return response()->json([
                    'code' => 404,
                    'message' => 'No user found',
                ]);
            }

            $user->transactions()->create([
                'subscription_id' => request('subscription_id'),
                'price' => request('price'),
            ]);

            return response()->json([
                'code' => 201,
                'message' => 'Successfully created transaction',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => 'Failed to create transaction',
                'error' => $th->getMessage(),
            ]);
        }
    }
}
