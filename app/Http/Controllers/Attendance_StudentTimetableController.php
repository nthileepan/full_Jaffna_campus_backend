<?php

namespace App\Http\Controllers;

use App\Models\Attendance_Student;
use App\Models\Attendance_Department;
use App\Models\Attendance_Batch;
use App\Models\Attendance_StudentDepartment;
use App\Models\Attendance_StudentBatch;
use App\Models\Attendance_TimeTable;
use App\Models\Attendance_Slot;
use App\Models\Attendance_Module;
use Illuminate\Http\Request;

class Attendance_StudentTimetableController extends Controller
{
    /**
     * Get the student's timetable with slot details.
     *
     * @param int $studentId
     * @return \Illuminate\Http\Response
     */
    public function getStudentTimetable($studentId)
    {
        // Step 1: Retrieve the student's department
        $studentDepartment = Attendance_StudentDepartment::where('student_id', $studentId)
                                               ->first();

        if (!$studentDepartment) {
            return response()->json(['message' => 'Department not found for this student.'], 404);
        }

        // Step 2: Retrieve the student's batch
        $studentBatch = Attendance_StudentBatch::where('student_id', $studentId)
                                    ->first();

        if (!$studentBatch) {
            return response()->json(['message' => 'Batch not found for this student.'], 404);
        }

        // Step 3: Find the student's timetable using department_id and batch_id
        $timeTables = Attendance_TimeTable::where('department_id', $studentDepartment->department_id)
                               ->where('batch_id', $studentBatch->batch_id)
                               ->get();

        if ($timeTables->isEmpty()) {
            return response()->json(['message' => 'No timetable found for this student.'], 404);
        }

        // Step 4: Include slot and module details in the timetable
        $timeTablesWithDetails = $timeTables->map(function ($timeTable) {
            // Load the associated slot details for each timetable entry
            $slot = Attendance_Slot::find($timeTable->slot_id);

            // Load the associated module name for each timetable entry
            $module = Attendance_Module::find($timeTable->module_id);

            // Return the time table with the slot details and module name
            return [
                'time_table' => $timeTable,
                'slot' => $slot ? $slot : null, // In case there's no slot, return null
                'module_name' => $module ? $module->module_name : null, // Return module_name instead of id
            ];
        });

        return response()->json([
            'student_id' => $studentId,
            'time_tables' => $timeTablesWithDetails
        ]);
    }
}
