<?php

namespace App\Http\Controllers;

use App\Models\User;
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
                'formData.userPassword' => 'required|string|min:8',
            ]);

            $user = User::where('email', $formData['userMail'])->first();

            if (!$user || !Hash::check($formData['userPassword'], $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['Invalid credentials.'],
                ]);
            }

            $user->tokens()->delete();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'token' => $token,
            ]);
        }

        return response()->json([
            'message' => 'Invalid request'
        ], 400);
    }
}
