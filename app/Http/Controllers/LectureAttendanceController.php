<?php

namespace App\Http\Controllers;

use App\Models\Attendance_TimeTable;
use App\Models\Attendance_Student;
use App\Models\Attendance_Lecture;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LectureAttendanceController extends Controller
{
    /**
     * Get all students for a specific class/time table entry with attendance statistics
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getClassAttendance(Request $request)
    {
        // Validate request parameters
        $request->validate([
            'lecture_id' => 'required|integer',
            'time_table_id' => 'required|integer'
        ]);

        // Get the time table entry
        $timeTable = Attendance_TimeTable::with(['department', 'batch'])
            ->where('id', $request->time_table_id)
            ->where('lecturer_id', $request->lecture_id)
            ->first();

        if (!$timeTable) {
            return response()->json([
                'message' => 'Time table entry not found or lecturer is not assigned to this class.'
            ], 404);
        }

        // Get all students in the same department and batch
        $students = Attendance_Student::where('department', $timeTable->department_id)
            ->where('batch', $timeTable->batch_id)
            ->select([
                'id',
                'zoho_no',
                'first_name',
                'middle_name',
                'last_name',
                'department',
                'batch',
                'email',
                'phone_number'
            ])
            ->get();

        // Get attendance records for all students in this time table
        $attendanceRecords = Attendance::where('time_table_id', $request->time_table_id)
            ->where('lecture_id', $request->lecture_id)
            ->get()
            ->keyBy('student_id');

        // Calculate total classes for this time table
        $totalClasses = DB::table('attendance')
            ->where('time_table_id', $request->time_table_id)
            ->where('lecture_id', $request->lecture_id)
            ->select('attendance_at')
            ->distinct()
            ->count();

        $totalStudents = $students->count();
        $presentStudents = 0;

        // Format student data with attendance information
        $formattedStudents = $students->map(function ($student) use ($attendanceRecords, $totalClasses, &$presentStudents) {
            // Get student's attendance record
            $attendance = $attendanceRecords->get($student->id);
            
            // Calculate absences
            $studentAttendances = Attendance::where('student_id', $student->id)
                ->where('time_table_id', request('time_table_id'))
                ->where('lecture_id', request('lecture_id'))
                ->where('status', 'absent')
                ->count();

            // Update present students count
            if ($attendance && $attendance->status === 'present') {
                $presentStudents++;
            }

            return [
                'student_id' => $student->id,
                'zoho_no' => $student->zoho_no,
                'full_name' => trim($student->first_name . ' ' . 
                              ($student->middle_name ? $student->middle_name . ' ' : '') . 
                              $student->last_name),
                'email' => $student->email,
                'phone_number' => $student->phone_number,
                'attendance_status' => $attendance ? $attendance->status : 'absent',
                'days_absent' => $studentAttendances,
                'attendance_percentage' => $totalClasses > 0 
                    ? round(((($totalClasses - $studentAttendances) / $totalClasses) * 100), 2)
                    : 100
            ];
        });

        // Calculate overall attendance percentage
        $attendancePercentage = $totalStudents > 0 
            ? round(($presentStudents / $totalStudents) * 100, 2)
            : 0;

        // Format the response
        $response = [
            'class_details' => [
                'time_table_id' => $timeTable->id,
                'department' => $timeTable->department ? $timeTable->department->department_name : null,
                'batch' => $timeTable->batch ? $timeTable->batch->batch_name : null,
                'date' => $timeTable->date,
                'start_date' => $timeTable->start_date,
                'end_date' => $timeTable->end_date,
                'classroom' => $timeTable->classroom
            ],
            'attendance_statistics' => [
                'total_students' => $totalStudents,
                'present_students' => $presentStudents,
                'absent_students' => $totalStudents - $presentStudents,
                'attendance_percentage' => $attendancePercentage,
                'total_classes' => $totalClasses
            ],
            'students' => $formattedStudents
        ];

        return response()->json($response);
    }
}