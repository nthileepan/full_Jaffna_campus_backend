<?php

namespace App\Http\Controllers;
use App\Models\lectureModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LectureController extends Controller
{
    public function getAll(Request $request)
    {
        try {
            $all = lectureModel::all();
            return response()->json(['data' => $all], 200);
        } catch (\Exception $error) {
            Log::error('Error fetching lecture:', [
                'error' => $error->getMessage(),
                'stack' => $error->getTraceAsString()
            ]);

        }
    }

    // Validation for store and update functions
    private function validatelecture(Request $request)
    {
        return Validator::make($request->all(), [
            'lecture_name' => 'required|string|max:100',
            'lecture_phone_number' => 'required|integer',
            'lecture_gender' => 'required|in:Male,Female',
            'off_day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday',
            'lecture_status' => 'required|in:Active,Inactive',
        ]);
    }


    // Store a new lecture
    public function store(Request $request)
    {
        try {
            // Validate request data
            $validator = $this->validatelecture($request);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $lecture = new lectureModel();

            $lecture->lecture_name = $request->lecture_name;
            $lecture->lecture_phone_number = $request->lecture_phone_number;
            $lecture->lecture_gender = $request->lecture_gender;
            $lecture->off_day = $request->off_day;
            $lecture->lecture_status = $request->lecture_status;
            $lecture->save();

            return response()->json([
                'message' => 'Lecture added successfully',
                'data' => $lecture,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error adding lecture:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    // // Show a single lecture by ID


    public function show($lecture_id)
    {
        try {
            // Validate that lecture_id is numeric
            if (!is_numeric($lecture_id)) {
                return response()->json(['message' => 'Invalid department ID'], 400);
            }

            // Find the lecture or fail if it doesn't exist
            $lecture = lectureModel::findOrFail($lecture_id);

            // Return the lecture details if found
            return response()->json([
                'message' => 'lecture found',
                'data' => $lecture
            ], 200);
        } catch (ModelNotFoundException $e) {
            // Handle the case when the department is not found
            return response()->json(['message' => 'lecture not found'], 404);
        } catch (\Exception $e) {
            // Handle other unexpected exceptions
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }


    // Update

    public function update(Request $request, $lecture_id)
    {
        try {
            $validator = $this->validateLecture($request);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $lecture = lectureModel::find($lecture_id);
            if (!$lecture) {
                Log::error("Lecture with ID {$lecture_id} not found.");
                return response()->json(['message' => 'Lecture not found'], 404);
            }

            Log::info('Found lecture, updating...', ['lecture_id' => $lecture_id, 'data' => $request->all()]);

            $lecture->lecture_name = $request->lecture_name;
            $lecture->lecture_phone_number = $request->lecture_phone_number;
            $lecture->lecture_gender = $request->lecture_gender;
            $lecture->off_day = $request->off_day;
            $lecture->lecture_status = $request->lecture_status;
            $lecture->save();

            return response()->json([
                'message' => 'Lecture updated successfully',
                'data' => $lecture
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error updating lecture:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);
            return response()->json(['error' => 'Unable to update lecture'], 500);
        }
    }

    // // Delete a lecture
    public function destroy($lecture_id)
    {
        try {
            $lecture = lectureModel::find($lecture_id);
            if ($lecture) {
                $lecture->delete();
                return response()->json(['message' => 'lecture deleted successfully'], 200);
            } else {
                return response()->json(['message' => 'lecture not found'], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error deleting lecturet:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Unable to delete lecture'], 500);
        }
    }
}
