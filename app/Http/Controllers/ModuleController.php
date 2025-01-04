<?php

namespace App\Http\Controllers;

use App\Models\moduleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class ModuleController extends Controller
{
    // Fetch all departments
    public function getAll(Request $request)
    {
        try {
            // Retrieve all modules with their associated department
            $all = moduleModel::with('department')->get();
    
            // Transform data to replace `department_shortname` with the department's name
            $result = $all->map(function ($module) {
                return [
                    'module_id' => $module->module_id,
                    'module_name' => $module->module_name,
                    'department_shortname' => $module->department ? $module->department->department_shortname : null,
                    'module_hours' => $module->module_hours,
                    'module_status' => $module->module_status,
                    'created_at' => $module->created_at,
                    'updated_at' => $module->updated_at,
                ];
            });
    
            return response()->json(['data' => $result], 200);
        } catch (\Exception $error) {
            \Log::error('Error fetching records:', ['error' => $error->getMessage()]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
    
    // Validation for store and update functions
    private function validateModule(Request $request)
    {
        return Validator::make($request->all(), [
            'module_name' => 'required|string|max:255',
            'department_shortname' => 'required|string|max:255',
            'module_hours' => 'required|int|',
            'module_status' => 'required|in:Active,Inactive', // Ensure validation accepts both 'Active' and 'Inactive'
        ]);
    }


    // Store a new department
    public function store(Request $request)
    {
        $validator = $this->validateModule($request);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $module = moduleModel::create($request->all());
        return response()->json(['message' => 'Module added successfully', 'data' => $module], 201);
    }

    // Show a single module by ID
    public function show($module_id)
    {
        try {
            if (!is_numeric($module_id)) {
                return response()->json(['message' => 'Invalid module ID'], 400);
            }

            $module = moduleModel::findOrFail($module_id);

            return response()->json([
                'message' => 'Module found',
                'data' => $module
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Module not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }





    public function update(Request $request, $module_id)
    {
        $validator = $this->validateModule($request);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $module = moduleModel::find($module_id);
        if (!$module) {
            return response()->json(['message' => 'Module not found'], 404);
        }

        $module->update($request->all());
        return response()->json(['message' => 'Module updated successfully', 'data' => $module], 200);
    }

// Filter modules by department ID
public function filterByDepartment($department_id)
{
    try {
        // Validate that department_id is numeric
        if (!is_numeric($department_id)) {
            return response()->json(['message' => 'Invalid department ID'], 400);
        }

        // Find modules where department_shortname matches the given department ID
        $modules = moduleModel::with('department') // Load the related department
            ->where('department_shortname', $department_id)
            ->get();

        // Check if any modules were found
        if ($modules->isEmpty()) {
            return response()->json(['message' => 'No modules found for this department'], 404);
        }

        // Transform the data to include the department's short name
        $result = $modules->map(function ($module) {
            return [
                'module_id' => $module->module_id,
                'module_name' => $module->module_name,
                'department_shortname' => $module->department ? $module->department->department_shortname : null,
                'module_hours' => $module->module_hours,
                'module_status' => $module->module_status,
                'created_at' => $module->created_at,
                'updated_at' => $module->updated_at,
            ];
        });

        return response()->json([
            'message' => 'Modules retrieved successfully',
            'data' => $result
        ], 200);
    } catch (\Exception $e) {
        Log::error('Error filtering modules by department:', ['error' => $e->getMessage()]);
        return response()->json(['error' => 'Internal Server Error'], 500);
    }
}





    // Delete a module
    public function destroy($module_id)
    {
        try {
            $module = moduleModel::find($module_id);
            if ($module) {
                $module->delete();
                return response()->json(['message' => 'Module deleted successfully'], 200);
            } else {
                return response()->json(['message' => 'Module not found'], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error deleting module:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Unable to delete module'], 500);
        }
    }
}
