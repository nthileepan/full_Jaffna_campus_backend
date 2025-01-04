<?php

namespace App\Http\Controllers;

use App\Models\Timetable;
use App\Models\lectureModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TimetableController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Log the incoming request data
            Log::info('Timetable data received: ', $request->all());

            // Define validation rules
            $validator = Validator::make($request->all(), [
                'subject' => 'required|string|max:255',
                'lecture' => 'required|integer|exists:lecture,lecture_id', // Ensure 'lecture' is an integer and exists in 'lectures_model' table
                'department' => 'nullable|string|max:255',
                'batch' => 'nullable|string|max:255',
                'lecture_hall' => 'nullable|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            // Check for validation failures
            if ($validator->fails()) {
                Log::error('Validation failed: ', $validator->errors()->toArray());
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if the lecture exists (if you are using 'lecture_id' as the foreign key)
            $lectureExists = lectureModel::where('lecture_id', $request->lecture)->exists();

            if (!$lectureExists) {
                Log::warning('Lecture not found: ' . $request->lecture);
                return response()->json([
                    'message' => 'Invalid lecture specified',
                ], 400);
            }

            // Create a new timetable record
            $timetable = new Timetable();
            $timetable->subject = $request->input('subject');
            $timetable->lecture = $request->input('lecture');
            $timetable->department = $request->input('department', '');
            $timetable->batch = $request->input('batch', '');
            $timetable->lecture_hall = $request->input('lecture_hall', '');
            $timetable->start_date = $request->input('start_date');
            $timetable->end_date = $request->input('end_date');
            
            // Save the timetable
            $timetable->save();

            // Log successful save
            Log::info('Timetable saved successfully', ['id' => $timetable->id]);

            // Return success response
            return response()->json([
                'message' => 'Timetable saved successfully',
                'data' => $timetable
            ], 201);

        } catch (\Exception $e) {
            // Log the full error
            Log::error('Error saving timetable: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            // Return a generic error response
            return response()->json([
                'message' => 'An error occurred while saving the timetable.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function view()
{
    try {
        // Fetch all timetable records
        $timetables = Timetable::all()->map(function ($timetable) {
            // Assuming the lecture relationship is correctly defined in the Timetable model
            $lecture = lectureModel::find($timetable->lecture); // Find the lecture by ID

            return [
                'id' => $timetable->id,
                'subject' => $timetable->subject,
                'lecture' => $lecture ? $lecture->lecture_name : null, // Add null check
                'lecture_id' => $timetable->lecture,
                'department' => $timetable->department,
                'batch' => $timetable->batch,
                'lecture_hall' => $timetable->lecture_hall,
                'start_date' => $timetable->start_date,
                'end_date' => $timetable->end_date
            ];
        });

        // Return the timetable data as JSON response
        return response()->json([
            'message' => 'Timetables fetched successfully',
            'data' => $timetables
        ], 200);
    } catch (\Exception $e) {
        Log::error('Error fetching timetables: ' . $e->getMessage());
        return response()->json([
            'message' => 'An error occurred while fetching the timetables.',
            'error' => $e->getMessage()
        ], 500);
    }
}


public function update(Request $request, $id)
{
    try {
        // Log the incoming request data
        Log::info('Timetable update data received: ', $request->all());

        // Find the existing timetable entry
        $timetable = Timetable::findOrFail($id);

        // Define validation rules
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'lecture' => 'required|integer|exists:lecture,lecture_id',
            'department' => 'nullable|string|max:255',
            'batch' => 'nullable|string|max:255',
            'lecture_hall' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Check for validation failures
        if ($validator->fails()) {
            Log::error('Validation failed: ', $validator->errors()->toArray());
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if the lecture exists
        $lectureExists = lectureModel::where('lecture_id', $request->lecture)->exists();

        if (!$lectureExists) {
            Log::warning('Lecture not found: ' . $request->lecture);
            return response()->json([
                'message' => 'Invalid lecture specified',
            ], 400);
        }

        // Update the timetable record
        $timetable->subject = $request->input('subject');
        $timetable->lecture = $request->input('lecture');
        $timetable->department = $request->input('department', '');
        $timetable->batch = $request->input('batch', '');
        $timetable->lecture_hall = $request->input('lecture_hall', '');
        $timetable->start_date = $request->input('start_date');
        $timetable->end_date = $request->input('end_date');
        
        // Save the updated timetable
        $timetable->save();

        // Log successful update
        Log::info('Timetable updated successfully', ['id' => $timetable->id]);

        // Return success response
        return response()->json([
            'message' => 'Timetable updated successfully',
            'data' => $timetable
        ], 200);

    } catch (\Exception $e) {
        // Log the full error
        Log::error('Error updating timetable: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'request_data' => $request->all()
        ]);

        // Return a generic error response
        return response()->json([
            'message' => 'An error occurred while updating the timetable.',
            'error' => $e->getMessage()
        ], 500);
    }
}

}
