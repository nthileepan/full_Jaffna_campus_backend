<?php

namespace App\Http\Controllers;

use App\Models\assignModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssigntomoduleController extends Controller
{
    public function getAll()
    {
        $all = assignModule::all();
        return response()->json(['data' => $all], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lecture_name' => 'required|string|max:100',
            'department_name' => 'required|string|max:100',
            'module_name' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $assignment = assignModule::create($request->all());
        return response()->json(['message' => 'Assignment added successfully', 'data' => $assignment], 201);
    }

    public function update(Request $request, $id)
    {
        $assignment = assignModule::find($id);

        if (!$assignment) {
            return response()->json(['message' => 'Assignment not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'lecture_name' => 'required|string|max:100',
            'department_name' => 'required|string|max:100',
            'module_name' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $assignment->update($request->all());
        return response()->json(['message' => 'Assignment updated successfully'], 200);
    }

    public function destroy($id)
    {
        $assignment = assignModule::find($id);

        if (!$assignment) {
            return response()->json(['message' => 'Assignment not found'], 404);
        }

        $assignment->delete();
        return response()->json(['message' => 'Assignment deleted successfully'], 200);
    }
}
