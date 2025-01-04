<?php

namespace App\Http\Controllers;

use App\Models\Attendance_Slot;
use Illuminate\Http\Request;
use App\Models\Slot;
use Illuminate\Support\Facades\Log;
class SlotController extends Controller
{
    // Fetch all slots
    public function index()
    {
        $slots = Attendance_Slot::all();
        return response()->json($slots);
    }

    // Fetch a specific slot by ID
    public function show($id)
    {
        $slot = Attendance_Slot::find($id);

        if (!$slot) {
            return response()->json(['message' => 'Slot not found'], 404);
        }

        return response()->json($slot);
    }

    // Create a new slot
    public function store(Request $request)
    {
        $data = $request->validate([
            'slot_name' => 'required',
            'start_time' => 'required',
            'end_time' => 'required', // Ensure end time is after start time
        ]);

        $slot = Attendance_Slot::create($data);

        return response()->json($slot, 201);
    }

    // Update an existing slot
    public function update(Request $request, $id)
    {
        try {
            // Find the slot first
            $slot = Attendance_Slot::findOrFail($id);
    
            // Validate the incoming data
            $data = $request->validate([
                'slot_name' => 'required|string|max:255',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
            ]);
    
            // Log incoming data for debugging
            Log::info('Update Slot Request', [
                'id' => $id,
                'input_data' => $request->all(),
                'validated_data' => $data
            ]);
    
            // Update the slot
            $slot->update($data);
    
            // Log successful update
            Log::info('Slot Updated Successfully', [
                'id' => $slot->id,
                'updated_data' => $slot->toArray()
            ]);
    
            return response()->json($slot);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors
            Log::error('Slot Update Validation Error', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
    
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Slot not found
            Log::error('Slot Not Found', ['id' => $id]);
            return response()->json([
                'message' => 'Slot not found'
            ], 404);
    
        } catch (\Exception $e) {
            // Catch any other unexpected errors
            Log::error('Unexpected Error in Slot Update', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Delete a slot
    public function destroy($id)
    {
        $slot = Attendance_Slot::find($id);

        if (!$slot) {
            return response()->json(['message' => 'Slot not found'], 404);
        }

        $slot->delete();

        return response()->json(['message' => 'Slot deleted successfully']);
    }
}
