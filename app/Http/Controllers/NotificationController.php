<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{
    public function checkNotification()
    {
        // Get the start time from cache or initialize
        $startTime = Cache::get('start_time');

        if (!$startTime) {
            // Store the current time if not already set
            $startTime = now();
            Cache::put('start_time', $startTime, now()->addMinutes(1)); // Store for 1 minute
        }

        // Check if 1 minute has passed
        if (now()->diffInMinutes($startTime) >= 1) {
            return response()->json([
                'message' => 'One minute has finished.',
            ]);
        }

        return response()->json([
            'message' => null,
        ]);
    }
}
