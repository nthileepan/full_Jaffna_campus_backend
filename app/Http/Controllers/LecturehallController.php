<?php

namespace App\Http\Controllers;

use App\Models\lecturehallModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class LecturehallController extends Controller
{
    // Fetch all lecturehall
    public function getAll(Request $request)
    {
        try {
            $all = lecturehallModel::all();
            return response()->json(['data' => $all], 200);
        } catch (\Exception $error) {
            Log::error('Error fetching records:', ['error' => $error->getMessage()]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    // Validation for store and update functions
    private function validateLecturehall(Request $request)
    {
        return Validator::make($request->all(), [
            'lecturehall_name' => 'required|string|max:255',
            'lecturehall_shortname' => 'required|string|max:255',
            'lecturehall_status' => 'required|in:Active,Inactive', // Ensure validation accepts both 'Active' and 'Inactive'
        ]);
    }


    // Store a new department
    public function store(Request $request)
    {
        $validator = $this->validateLecturehall($request);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $lecturehall = lecturehallModel::create($request->all());
        return response()->json(['message' => 'Lecturehall added successfully', 'data' => $lecturehall], 201);
    }

    // Show a single module by ID
    public function show($lecturehall_id)
    {
        try {
            if (!is_numeric($lecturehall_id)) {
                return response()->json(['message' => 'Invalid module ID'], 400);
            }

            $lecturehall = lecturehallModel::findOrFail($lecturehall_id);

            return response()->json([
                'message' => 'Lecturehall found',
                'data' => $lecturehall
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Lecturehall not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }





    public function update(Request $request, $lecturehall_id)
    {
        $validator = $this->validateLecturehall($request);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $lecturehall = lecturehallModel::find($lecturehall_id);
        if (!$lecturehall) {
            return response()->json(['message' => 'Lecturehall not found'], 404);
        }

        $lecturehall->update($request->all());
        return response()->json(['message' => 'Lecturehall updated successfully', 'data' => $lecturehall], 200);
    }

    // Delete a module
    public function destroy($lecturehall_id)
    {
        try {
            $lecturehall = lecturehallModel::find($lecturehall_id);
            if ($lecturehall) {
                $lecturehall->delete();
                return response()->json(['message' => 'Lecturehall deleted successfully'], 200);
            } else {
                return response()->json(['message' => 'Lecturehall not found'], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error deleting lecturehall:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Unable to delete lecturehall'], 500);
        }
    }
}
