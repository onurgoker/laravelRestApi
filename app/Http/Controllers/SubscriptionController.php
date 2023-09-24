<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Models\User;
use App\Models\Subscription;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;


class SubscriptionController extends Controller
{
    private $subscriptionLength;
    private $subscriptionPrice;

    public function __construct()
    {
        $this->subscriptionLength = env('APP_SUBSCRIPTION_DURATION_IN_MONTH');
    }
    public function get(): JsonResponse
    {
        $user = User::find(request('id'));

        if (!$user) {
            return response()->json([
                'code' => 404, // 'code' => '404
                'message' => 'No user found',
            ], 404);
        }

        return response()->json([
            'code' => 200, // 'code' => '200
            'message' => 'Successfully retrieved subscription',
            'data' => ['subscriptions' => $user->subscription, 'transactions' => $user->transactions],
        ], 200);
    }

    public function store(): JsonResponse
    {
        try {
            $validator = Validator::make(request()->all(), [
                'user_id' => 'required',
                'renewed_at' => 'required | string',
            ]);

            if($validator->fails()) {
                return response()->json([
                    'code' => 422,
                    'message' => 'Invalid request',
                    'error' => $validator->errors(),
                ], 422);
            }

            Subscription::create([
                'user_id' => request('user_id'),
                'price' => request('price'),
                'renewed_at' => request('renewed_at'),
                'expired_at' => date(
                    'Y-m-d H:i:s', strtotime(request('renewed_at') . " + {$this->subscriptionLength} months")),
            ]);

            return response()->json([
                'code' => 201,
                'message' => 'Successfully created subscription',
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => 'Failed to create subscription',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function update(): JsonResponse
    {
        try {
            $validator = Validator::make(request()->all(), [
                'renewed_at' => 'required | string',
            ]);

            if($validator->fails()) {
                return response()->json([
                    'code' => 422,
                    'message' => 'Invalid request',
                    'error' => $validator->errors(),
                ], 422);
            }

            $subscription = Subscription::find(request('id'));

            if(!$subscription) {
                return response()->json([
                    'code' => 404,
                    'message' => 'No subscription found',
                ], 404);
            }

            $subscription->update([
                'renewed_at' => request('renewed_at'),
                'expired_at' => date(
                    'Y-m-d H:i:s', strtotime(request('renewed_at') . " + {$this->subscriptionLength} months")),
            ]);

            return response()->json([
                'code' => 200,
                'message' => 'Successfully updated subscription',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => 'Failed to create subscription',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function delete(): JsonResponse
    {
        try {
            $subscription = Subscription::find(request('id'));

            if(!$subscription) {
                return response()->json([
                    'code' => 404,
                    'message' => 'No subscription found',
                ], 404);
            }

            $subscription->delete();

            return response()->json([
                'code' => 200,
                'message' => 'Successfully deleted subscription',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => 'Failed to delete subscription',
                'error' => $th->getMessage(),
            ], 500);
        }
    }
}
