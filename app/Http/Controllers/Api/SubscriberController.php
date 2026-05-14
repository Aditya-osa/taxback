<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriberController extends Controller
{
    /**
     * Store a new newsletter subscriber.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter a valid email address.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Check if already subscribed
        $existing = Subscriber::where('email', $request->email)->first();

        if ($existing) {
            if ($existing->status === 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'This email is already subscribed to our newsletter.',
                ], 409);
            }

            // Re-subscribe if previously unsubscribed
            $existing->update([
                'status' => 'active',
                'subscribed_at' => now(),
                'unsubscribed_at' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Welcome back! You have been re-subscribed to our newsletter.',
            ], 200);
        }

        // Create new subscriber
        Subscriber::create([
            'email' => $request->email,
            'status' => 'active',
            'subscribed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for subscribing to our newsletter!',
        ], 201);
    }

    /**
     * Unsubscribe from the newsletter.
     */
    public function unsubscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter a valid email address.',
            ], 422);
        }

        $subscriber = Subscriber::where('email', $request->email)->first();

        if (!$subscriber || $subscriber->status === 'unsubscribed') {
            return response()->json([
                'success' => false,
                'message' => 'This email is not currently subscribed.',
            ], 404);
        }

        $subscriber->update([
            'status' => 'unsubscribed',
            'unsubscribed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'You have been successfully unsubscribed.',
        ], 200);
    }

    /**
     * List all subscribers (admin only).
     */
    public function index()
    {
        $subscribers = Subscriber::orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $subscribers,
            'total' => $subscribers->count(),
            'active' => $subscribers->where('status', 'active')->count(),
        ]);
    }

    /**
     * Delete a subscriber (admin only).
     */
    public function destroy($id)
    {
        $subscriber = Subscriber::findOrFail($id);
        $subscriber->delete();

        return response()->json([
            'success' => true,
            'message' => 'Subscriber removed successfully.',
        ]);
    }
}
