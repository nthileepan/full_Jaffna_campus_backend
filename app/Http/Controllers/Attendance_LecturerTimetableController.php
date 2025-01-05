<?php

namespace App\Http\Controllers;

use App\Models\lectureModel;
use App\Models\Attendance_TimeTable;
use App\Models\Attendance_Slot;
use App\Models\Attendance_Module;
use Illuminate\Http\Request;

class Attendance_LecturerTimetableController extends Controller
{
    /**
     * Get the lecturer's timetable with slot details.
     *
     * @param int $lectureId
     * @return \Illuminate\Http\Response
     */
    public function getLecturerTimetable($lectureId)
    {
        // Step 1: Retrieve the lecturer
        $lecturer = lectureModel::find($lectureId);

        if (!$lecturer) {
            return response()->json(['message' => 'Lecturer not found.'], 404);
        }

        // Step 2: Find the lecturer's timetable entries
        $timeTables = Attendance_TimeTable::where('lecturer_id', $lectureId)
                               ->get();

        if ($timeTables->isEmpty()) {
            return response()->json(['message' => 'No timetable found for this lecturer.'], 404);
        }

        // Step 3: Include slot and module details in the timetable
        $timeTablesWithDetails = $timeTables->map(function ($timeTable) {
            // Load the associated slot details for each timetable entry
            $slot = Attendance_Slot::find($timeTable->slot_id);

            // Load the associated module name for each timetable entry
            $module = Attendance_Module::find($timeTable->module_id);

            // Return the time table with the slot details and module name
            return [
                'time_table' => $timeTable,
                'slot' => $slot ? $slot : null,
                'module_name' => $module ? $module->module_name : null,
                'department' =>  null,
                'batch' => $timeTable->batch ? $timeTable->batch->batch_name : null,
            ];
        });

        return response()->json([
            'lecturer_id' => $lectureId,
            'time_tables' => $timeTablesWithDetails
        ]);
    }
}