<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        if ($request->has('formData')) {
            $formData = $request->input('formData');

            $validated = $request->validate([
                'formData.userMail' => 'required|email',
                'formData.userPassword' => 'required|string',
            ]);

            $user = User::with(['roles', 'student'])->where('email', $formData['userMail'])->first();

            if (!$user || !Hash::check($formData['userPassword'], $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['Invalid credentials.'],
                ]);
            }

            // Delete existing tokens
            $user->tokens()->delete();
            $token = $user->createToken('auth_token')->plainTextToken;

            // Prepare roles and permissions
            $roles = $user->roles->pluck('slug')->toArray();
            $privileges = [];
            
            foreach ($user->roles as $role) {
                $privileges = array_merge($privileges, $role->privileges->pluck('slug')->toArray());
            }

            // Prepare response data
            $responseData = [
                'message' => 'Login successful',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $roles,
                    'privileges' => array_unique($privileges),
                ],
                'token' => $token,
            ];

            // Add student information if user is a student
            if ($user->student) {
                $responseData['user']['student'] = [
                    'id' => $user->student->id,
                    'zoho_no' => $user->student->zoho_no,
                    'department' => $user->student->department,
                    'batch' => $user->student->batch,
                    'first_name' => $user->student->first_name,
                    'last_name' => $user->student->last_name,
                ];
            }

            return response()->json($responseData);
        }

        return response()->json([
            'message' => 'Invalid request'
        ], 400);
    }

    public function logout(Request $request)
    {
        // Revoke all tokens
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        $user->load(['roles.privileges', 'student']);

        $roles = $user->roles->pluck('slug')->toArray();
        $privileges = [];
        
        foreach ($user->roles as $role) {
            $privileges = array_merge($privileges, $role->privileges->pluck('slug')->toArray());
        }

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $roles,
            'privileges' => array_unique($privileges),
        ];

        if ($user->student) {
            $userData['student'] = [
                'id' => $user->student->id,
                'zoho_no' => $user->student->zoho_no,
                'department' => $user->student->department,
                'batch' => $user->student->batch,
                'first_name' => $user->student->first_name,
                'last_name' => $user->student->last_name,
            ];
        }

        return response()->json([
            'user' => $userData
        ]);
    }
}