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
        // Step 1: Retrieve the student with their department and batch
        $student = Attendance_Student::find($studentId);

        if (!$student) {
            return response()->json(['message' => 'Student not found.'], 404);
        }

        if (!$student->department || !$student->batch) {
            return response()->json(['message' => 'Department or batch not assigned to this student.'], 404);
        }

        // Step 2: Find the student's timetable using department and batch
        $timeTables = Attendance_TimeTable::where('department_id', $student->department)
                               ->where('batch_id', $student->batch)
                               ->get();

        if ($timeTables->isEmpty()) {
            return response()->json(['message' => 'No timetable found for this student.'], 404);
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
