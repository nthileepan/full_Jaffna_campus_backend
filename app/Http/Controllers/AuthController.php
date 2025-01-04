<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\StudentUser;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'emailId' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Check for user credentials
        $student = StudentUser::where('emailId', $request->emailId)->first();

        if (!$student) {
            return response()->json([
                'status' => false,
                'message' => 'Email not found',
            ], 404);
        }

        if ($student->password !== $request->password) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        // Successful login
        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'data' => [
                'id' => $student->student_id,
                'email' => $student->emailId,
                'status' => $student->status,
            ],
        ]);
    }

}
