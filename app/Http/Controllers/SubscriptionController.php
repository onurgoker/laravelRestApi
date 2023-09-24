<?php

namespace App\Http\Controllers;

use Exception;
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
        $this->middleware('auth:api');
        $this->subscriptionLength = env('APP_SUBSCRIPTION_DURATION_IN_MONTH');
        $this->subscriptionPrice = env('APP_SUBSCRIPTION_PRICE');
    }
    public function get(): JsonResponse
    {
        $user = User::findOrFail(request('id'));

        if ($user->isEmpty()) {
            return response()->json([
                'code' => 404, // 'code' => '404
                'message' => 'No user found',
            ]);
        }

        return response()->json([
            'code' => 200, // 'code' => '200
            'message' => 'Successfully retrieved subscription',
            'data' => ['subscriptions' => $user->subscription, 'transactions' => $user->transactions],
        ]);
    }

    public function store(): JsonResponse
    {
        try {
            $validator = Validator::make(request()->all(), [
                'user_id' => 'required | number',
                'renewed_at' => 'required | string',
            ]);

            if($validator->fails()) {
                return response()->json([
                    'code' => 422,
                    'message' => 'Invalid request',
                    'error' => $validator->errors(),
                ]);
            }

            Subscription::create([
                'user_id' => request('user_id'),
                'price' => $this->subscriptionPrice,
                'renewed_at' => request('renewed_at'),
                'expired_at' => date(
                    'Y-m-d H:i:s', strtotime(request('renewed_at') . " + {$this->subscriptionLength} months")),
            ]);

            return response()->json([
                'code' => 201,
                'message' => 'Successfully created subscription',
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => 'Failed to create subscription',
                'error' => $th->getMessage(),
            ]);
        }
    }

    public function update(): JsonResponse
    {
        try {
            $validator = Validator::make(request()->all(), [
                'user_id' => 'required | number',
                'renewed_at' => 'required | string',
            ]);

            if($validator->fails()) {
                return response()->json([
                    'code' => 422,
                    'message' => 'Invalid request',
                    'error' => $validator->errors(),
                ]);
            }

            $subscription = Subscription::findOrFail(request('id'));

            if($subscription->isEmpty()) {
                return response()->json([
                    'code' => 404,
                    'message' => 'No subscription found',
                ]);
            }

            $subscription->update([
                'renewed_at' => request('renewed_at'),
                'expired_at' => date(
                    'Y-m-d H:i:s', strtotime(request('renewed_at') . " + {$this->subscriptionLength} months")),
            ]);

            return response()->json([
                'code' => 200,
                'message' => 'Successfully updated subscription',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => 'Failed to create subscription',
                'error' => $th->getMessage(),
            ]);
        }
    }

    public function delete(): JsonResponse
    {
        try {
            $subscription = Subscription::findOrFail(request('id'));

            if($subscription->isEmpty()) {
                return response()->json([
                    'code' => 404,
                    'message' => 'No subscription found',
                ]);
            }

            $subscription->delete();

            return response()->json([
                'code' => 200,
                'message' => 'Successfully deleted subscription',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => 'Failed to delete subscription',
                'error' => $th->getMessage(),
            ]);
        }



    }
}
