<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance_Slot as slots;

class Attendance_SlotController extends Controller
{
    public function getAllSlot()
    {
        try {
            // Retrieve all districts
            $slots = slots::all();
            return response()->json($slots, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }
}
