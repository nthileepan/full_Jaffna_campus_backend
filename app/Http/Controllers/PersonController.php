<?php

namespace App\Http\Controllers;

use App\Models\departmentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class PersonController extends Controller
{
    // Fetch all departments
    public function getAll(Request $request)
    {
        try {
            $all = departmentModel::all();
            return response()->json(['data' => $all], 200);
        } catch (\Exception $error) {
            Log::error('Error fetching records:', ['error' => $error->getMessage()]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    // Validation for store and update functions
    private function validateDepartment(Request $request)
    {
        return Validator::make($request->all(), [
            'department_name' => 'required|string|max:255',
            'department_shortname' => 'required|string|max:100',
            'status' => 'required|in:Active,Inactive', // Ensure validation accepts both 'Active' and 'Inactive'
        ]);
    }

    // Store a new department
    public function store(Request $request)
    {
        try {
            // Validate request data
            $validator = $this->validateDepartment($request);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $department = new departmentModel();
            $department->department_name = $request->department_name;
            $department->department_shortname = $request->department_shortname;
            $department->status = $request->status;
            $department->save();

            return response()->json([
                'message' => 'Department added successfully',
                'data' => $department
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error adding department:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Unable to add department'], 500);
        }
    }

    // Show a single department by ID


    public function show($department_id)
    {
        try {
            if (!is_numeric($department_id)) {
                return response()->json(['message' => 'Invalid department ID'], 400);
            }
    
            $department = departmentModel::findOrFail($department_id);
    
            return response()->json([
                'message' => 'Department found',
                'data' => $department
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Department not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Update an existing department
    public function update(Request $request, $department_id)
    {
        try {
            // Validate request data
            $validator = $this->validateDepartment($request);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $department = departmentModel::find($department_id);
            if ($department) {
                $department->department_name = $request->department_name;
                $department->department_shortname = $request->department_shortname;
                $department->status = $request->status;
                $department->save();

                return response()->json([
                    'message' => 'Department updated successfully',
                    'data' => $department
                ], 200);
            } else {
                return response()->json(['message' => 'Department not found'], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error updating department:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Unable to update department'], 500);
        }
    }

    // Delete a department
    public function destroy($department_id)
    {
        try {
            $department = departmentModel::find($department_id);
            if ($department) {
                $department->delete();
                return response()->json(['message' => 'Department deleted successfully'], 200);
            } else {
                return response()->json(['message' => 'Department not found'], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error deleting department:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Unable to delete department'], 500);
        }
    }
}
