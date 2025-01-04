<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance_Otp;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Attendance_OtpController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'lecture_id' => 'required|integer', // Assuming lectures table exists
        ]);

        // Generate OTP and calculate expiration time
        $otpValue = Str::random(6); // Generates a 6-character random string
        $generatedAt = Carbon::now('Asia/Colombo');
        $expiresAt = $generatedAt->copy()->addMinutes(10);

        // Save OTP to the database
        $otp = Attendance_Otp::create([
            'lecture_id' => $request->lecture_id,
            'otp_value' => $otpValue,
            'generated_at' => $generatedAt,
            'expires_at' => $expiresAt,
        ]);

        // Return the OTP as a response
        return response()->json([
            'message' => 'OTP created successfully.',
            'otp' => $otpValue,
            'expires_at' => $expiresAt,
        ], 201);
    }
}
