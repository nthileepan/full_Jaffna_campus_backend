<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserDetailsController extends Controller
{
    /**
     * Get user roles and student/lecturer details by user ID
     *
     * @param Request $request
     * @param int $userId
     * @return JsonResponse
     */
    public function getUserDetails(Request $request, $userId)
    {
        try {
            $user = User::with([
                'roles', 
                'student' => function($query) {
                    $query->with([
                        'emergencyContact',
                        'nameOfCourse',
                        'qualifications',
                        'otherQualifications',
                        'applicantChecklist',
                        'studentImage',
                        'studentDateOfBirthCertificate',
                        'studentNic',
                        'personalStatement',
                        'whoWillPay',
                        'adminUse',
                        'department',
                        'batch'
                    ]);
                },
                'lecture'
            ])->findOrFail($userId);

            // Format the response data
            $response = [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->roles->map(function($role) {
                        return [
                            'id' => $role->id,
                            'name' => $role->name,
                            'slug' => $role->slug,
                            'description' => $role->description
                        ];
                    })
                ],
                // Include student details if exists
                'student' => $user->student ? [
                    'id' => $user->student->id,
                    'zoho_no' => $user->student->zoho_no,
                    'nic_no' => $user->student->nic_no,
                    'first_name' => $user->student->first_name,
                    'middle_name' => $user->student->middle_name,
                    'last_name' => $user->student->last_name,
                    'date_of_birth' => $user->student->date_of_birth,
                    'address' => $user->student->address,
                    'address_line' => $user->student->address_line,
                    'city' => $user->student->city,
                    'province' => $user->student->province,
                    'postal_code' => $user->student->postal_code,
                    'country' => $user->student->country,
                    'phone_number' => $user->student->phone_number,
                    'department' => $user->student->department,
                    'batch' => $user->student->batch,
                    'emergency_contact' => $user->student->emergencyContact,
                    'course' => $user->student->nameOfCourse,
                    'qualifications' => $user->student->qualifications,
                    'other_qualifications' => $user->student->otherQualifications,
                    'checklist' => $user->student->applicantChecklist,
                    'student_image' => $user->student->studentImage,
                    'birth_certificate' => $user->student->studentDateOfBirthCertificate,
                    'nic' => $user->student->studentNic,
                    'personal_statement' => $user->student->personalStatement,
                    'payment_info' => $user->student->whoWillPay,
                    'admin_use' => $user->student->adminUse
                ] : null,
                // Include lecturer details if exists
                'lecture' => $user->lecture ? [
                    'id' => $user->lecture->lecture_id,
                    'name' => $user->lecture->lecture_name,
                    'phone_number' => $user->lecture->lecture_phone_number,
                    'gender' => $user->lecture->lecture_gender,
                    'off_day' => $user->lecture->off_day,
                    'status' => $user->lecture->lecture_status
                ] : null
            ];

            return response()->json([
                'status' => 'success',
                'data' => $response
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch user details',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}