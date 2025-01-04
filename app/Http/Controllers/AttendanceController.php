<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance_Otp;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function verifyOtp(Request $request)
    {
        $validated = $request->validate([
            'otp_value' => 'required|string',
            'lecture_id' => 'required|integer',
            'student_id' => 'required|integer',
            'time_table_id' => 'required|integer',
        ]);

        $otpValue = $validated['otp_value'];
        $lectureId = $validated['lecture_id'];
        $studentId = $validated['student_id'];
        $timeTableId = $validated['time_table_id'];

        // Find OTP record
        $otp = Attendance_Otp::where('otp_value', $otpValue)
                  ->where('lecture_id', $lectureId)
                  ->first();

        if (!$otp) {
            return response()->json(['message' => 'Invalid OTP'], 400);
        }

        // Check if OTP is expired
        if (Carbon::now('Asia/Colombo')->greaterThan(Carbon::parse($otp->expires_at))) {
            return response()->json(['message' => 'OTP expired'], 400);
        }

        // Check if OTP was already used
        $existingAttendance = Attendance::where('student_id', $studentId)
                                         ->where('lecture_id', $lectureId)
                                         ->where('time_table_id', $timeTableId)
                                         ->first();

        if ($existingAttendance) {
            return response()->json(['message' => 'Attendance already marked for this student'], 400);
        }

        // Mark attendance
        Attendance::create([
            'student_id' => $studentId,
            'lecture_id' => $lectureId,
            'time_table_id' => $timeTableId,
            'attendance_at' => Carbon::now(),
            'verification_type' => 'OTP',
            'otp_used' => $otp->id,
            'status' => 'present',
        ]);

        return response()->json(['message' => 'Attendance marked successfully'], 200);
    }

    public function verifyQr(Request $request)
    {
        // For GET requests, data comes from query parameters
        $otpValue = $request->query('otp_value');
        $lectureId = $request->query('lecture_id');
        $timeTableId = $request->query('time_table_id');
        $studentId = $request->query('student_id');
        // Validate required parameters
        if (!$otpValue || !$lectureId || !$timeTableId || !$studentId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Missing required parameters'
            ], 400);
        }

        try {
            // Find OTP record
            $otp = Attendance_Otp::where('otp_value', $otpValue)
                     ->where('lecture_id', $lectureId)
                     ->first();

            if (!$otp) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid QR code'
                ], 400);
            }

            // Check if QR code is expired
            if (Carbon::now('Asia/Colombo')->greaterThan(Carbon::parse($otp->expires_at))) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'QR code has expired'
                ], 400);
            }

            // Check for existing attendance
            $existingAttendance = Attendance::where('student_id', $studentId)
                                          ->where('lecture_id', $lectureId)
                                          ->where('time_table_id', $timeTableId)
                                          ->first();

            if ($existingAttendance) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Attendance already marked for this student'
                ], 400);
            }

            // Mark attendance
            $attendance = Attendance::create([
                'student_id' => $studentId,
                'lecture_id' => $lectureId,
                'time_table_id' => $timeTableId,
                'attendance_at' => Carbon::now('Asia/Colombo'),
                'verification_type' => 'QR',
                'otp_used' => $otp->id,
                'status' => 'present',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Attendance marked successfully',
                'data' => [
                    'attendance_id' => $attendance->id,
                    'student_id' => $studentId,
                    'lecture_id' => $lectureId,
                    'time_table_id' => $timeTableId,
                    'marked_at' => $attendance->attendance_at->format('Y-m-d H:i:s')
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to mark attendance',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    
}
