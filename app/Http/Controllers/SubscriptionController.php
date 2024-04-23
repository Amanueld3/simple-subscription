<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscriptionRequest;
use App\Http\Requests\UpdateSubscriptionRequest;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function subscribers()
    {
        return User::whereHas('subscriptions')->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubscriptionRequest $request)
    {
        DB::beginTransaction();

        try {

            $user = User::create([
                'email' => $request->email,
            ]);

            foreach ($request->websites as $value) {
                Subscription::create([
                    'user_id' => $user->id,
                    'website_id' => $value,
                ]);
            }
            DB::commit();

            return response()->json(['message' => 'Subscribed successfully']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'An error occurred'], 500);
        }
    }
}
