<?php

namespace App\Http\Controllers;

use App\Models\admin_use;
use App\Models\applicant_checklist;
use App\Models\city;
use App\Models\course_type;
use App\Models\emergency_contact;
use App\Models\joined_from;
use App\Models\name_of_course;
use App\Models\other_qualifications;
use App\Models\personal_statement;
use App\Models\qualifications;
use App\Models\Student;
use App\Models\student_date_of_birth_certificate;
use App\Models\student_image;
use App\Models\student_nic;
use App\Models\who_will_pay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::with([
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
            'adminUse'
        ])->get();
        return response()->json(['students' => $students]);
    }


    public function create() {}


    public function savestudents(Request $request)
    {

        // $validatedData = $request->validate([
        //     // Personal Information
        //     'studentFirstName' => 'required|string|max:255',
        //     'studentMiddleName' => 'nullable|string|max:255',
        //     'studentLastName' => 'required|string|max:255',
        //     'month' => 'required|string|max:2',
        //     'day' => 'required|string|max:2',
        //     'year' => 'required|string|max:4',
        //     'streetAddress' => 'required|string|max:255',
        //     'streetAddressLine2' => 'nullable|string|max:255',
        //     'city' => 'required|string|max:255',
        //     'state' => 'required|string|max:255',
        //     'postalCode' => 'required|string|max:20',
        //     'country' => 'required|string|max:255',
        //     'email' => 'required|email|max:255',
        //     'phone' => 'required|string|max:15',

        //     // Emergency Contact
        //     'emergencyFirstName' => 'required|string|max:255',
        //     'emergencyLastName' => 'required|string|max:255',
        //     'emergencyRelationship' => 'required|string|max:255',
        //     'emergencyAddress' => 'required|string|max:255',
        //     'emergencyStreetAddressLine2' => 'nullable|string|max:255',
        //     'emergencyCity' => 'required|string|max:255',
        //     'emergencyState' => 'required|string|max:255',
        //     'emergencyPostalCode' => 'required|string|max:20',
        //     'emergencyPhoneNumber' => 'required|string|max:15',
        //     'emergencyEmail' => 'required|email|max:255',

        //     // Applied Information
        //     'appliedPreferredMode' => 'nullable|string|max:255',
        //     'programApplied' => 'required|string|max:255',
        //     'appliedStudentNumber' => 'nullable|string|max:50',
        //     'appliedCourseName' => 'required|string|max:255',

        //     // Exams and Qualifications
        //     'olExamName' => 'nullable|string|max:255',
        //     'alExamName' => 'nullable|string|max:255',
        //     'examOrEmploymentName' => 'nullable|string|max:255',
        //     'qualificationsStatement' => 'nullable|string',

        //     // Source of Information
        //     'newsPapersAdvertisement' => 'required|boolean',
        //     'seminarWebinar' => 'required|boolean',
        //     'socialMedia' => 'nullable|string|max:255',
        //     'openEvents' => 'nullable|string|max:255',
        //     'other' => 'nullable|string|max:255',
        //     'radio' => 'nullable|string|max:255',
        //     'bcasWebsite' => 'nullable|string|max:255',
        //     'leaflets' => 'nullable|string|max:255',
        //     'studentReview' => 'nullable|string|max:255',

        //     // Course Reason
        //     'courseReason' => 'nullable|string|max:255',

        //     // Financial Responsibility
        //     'selfChecked' => 'nullable|boolean',
        //     'parentsChecked' => 'nullable|boolean',
        //     'otherChecked' => 'nullable|boolean',
        //     'spouseChecked' => 'nullable|boolean',

        //     // Payer Information
        //     'payingName' => 'nullable|string|max:255',
        //     'payingAddress' => 'nullable|string|max:255',
        //     'payingOfficialAddress' => 'nullable|string|max:255',
        //     'payingCity' => 'nullable|string|max:255',
        //     'payingState' => 'nullable|string|max:255',
        //     'payingPostalCode' => 'nullable|string|max:20',
        //     'payingCountry' => 'nullable|string|max:255',
        //     'payingContactNumber' => 'nullable|string|max:15',
        //     'payingEmail' => 'nullable|email|max:255',

        //     // Scholarship and Payment Information
        //     'hasScholarship' => 'nullable|string|in:yes,no',
        //     'studentNumber' => 'nullable|string|max:50',
        //     'totalCourseFee' => 'nullable|numeric',
        //     'registrationFee' => 'nullable|numeric',
        //     'installments' => 'nullable|numeric',
        //     'paymentDiscount' => 'nullable|numeric',
        //     'date' => 'nullable|date',
        //     'paymentStatus' => 'nullable|string|in:approved,rejected',
        // ]);

        // return response()->json(['message' => 'Student saved successfully'], 200);
        try{
        $dateofmonth = "{$request->year}-{$request->month}-{$request->day}";


        $student = Student::create([
            'zoho_no' => $request->zohonumber,
            'nic_number' => $request->nic,
            'first_name' => $request->studentFirstName,
            'middle_name' => $request->studentMiddleName,
            'last_name' => $request->studentLastName,
            'date_of_birth' => $dateofmonth,
            'address' => $request->streetAddress,
            'address_line' => $request->streetAddressLine2,
            'city' => $request->city,
            'province' => $request->state,
            'postal_code' => $request->postalCode,
            'country' => $request->country,
            'email' => $request->email,
            'phone_number' => $request->phone,
        ]);

        $qrCodeContent = $student->id . '-' . $student->first_name;
        $qrCode = QrCode::format('png')->size(300)->generate($qrCodeContent);
        $base64QRCode = base64_encode($qrCode);
        $student->qr_code = 'data:image/png;base64,' . $base64QRCode;
        $student->save();

        $emergency_contact = emergency_contact::create([
            'student_id' => $student->id,
            'first_name' => $request->emergencyFirstName,
            'last_name' => $request->emergencyLastName,
            'relationship' => $request->emergencyRelationship,
            'address' => $request->emergencyAddress,
            'address_line' => $request->emergencyStreetAddressLine2,
            'city' => $request->emergencyCity,
            'province' => $request->emergencyState,
            'postal_code' => $request->emergencyPostalCode,
            'email' => $request->emergencyEmail,
            'phone_number' => $request->emergencyPhoneNumber,
        ]);

        $name_of_courses = name_of_course::create([
            'student_id' => $student->id,
            'preferred_mode' => $request->appliedPreferredMode,
            'program_applied_for' => $request->programApplied,
            'course_name' => $request->appliedCourseName,
            'student_number' => $request->appliedStudentNumber,
        ]);

        if ($request->hasFile('selectedOlFile')) {
            $file = $request->file('selectedOlFile');
            $olpath = $file->move(public_path('docs/documents'), $file->getClientOriginalName());
            $olpath = 'docs/documents/' . $file->getClientOriginalName();
        } else {
            $olpath = null;
        }

        if ($request->hasFile('selectedAlFile')) {
            $file = $request->file('selectedAlFile');
            $alpath = $file->move(public_path('docs/documents'), $file->getClientOriginalName());
            $alpath = 'docs/documents/' . $file->getClientOriginalName();
        } else {
            $alpath = null;
        }


        $qualifications = qualifications::create([
            'student_id' => $student->id,
            'olexam' => $request->olExamName,
            'olpath' => $olpath,
            'alexam' => $request->alExamName,
            'alpath' => $alpath,

        ]);


        $other_qualification = other_qualifications::create([
            'student_id' => $student->id,
            'qualifications' => $request->examOrEmploymentName,
            'qualifications_line' => $request->qualificationsStatement,
        ]);



        $ap_check = applicant_checklist::create([
            'student_id' => $student->id,
            'newspaper' => $request->newsPapersAdvertisement,
            'seminar' => $request->seminarWebinar,
            'social_media' => $request->socialMedia,
            'open_events' => $request->openEvents,
            'bcas_website' => $request->bcasWebsite,
            'leaflets' => $request->leaflets,
            'student_review' => $request->studentReview,
            'radio' => $request->radio,
            'other' => $request->other,
        ]);



        if ($request->hasFile('selectedImageFile')) {
            $file = $request->file('selectedImageFile');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;
            $file->move(public_path('docs/image'), $fileName);
            $image = 'docs/image/' . $fileName;
        } else {
            $image = null;
        }

        $st_image = student_image::create([
            'student_id' => $student->id,
            'image' => $image,
        ]);

        if ($request->hasFile('selectedFile1')) {
            $file = $request->file('selectedFile1');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;
            $file->move(public_path('docs/documets'), $fileName);
            $st_dob = 'docs/documets/' . $fileName;
        } else {
            $st_dob = null;
        }

        $st_dob_c = student_date_of_birth_certificate::create([
            'student_id' => $student->id,
            'date_of_birth_certificate' => $st_dob,
        ]);


        if ($request->hasFile('selectedFile2')) {
            $file = $request->file('selectedFile2');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;
            $file->move(public_path('docs/documets'), $fileName);
            $st_nic = 'docs/documets/' . $fileName;
        } else {
            $st_nic = null;
        }

        $nic = student_nic::create([
            'student_id' => $student->id,
            'nic' => $st_nic,
        ]);


        $statment = personal_statement::create([
            'student_id' => $student->id,
            'course_reason' => $request->courseReason,
            'self' => $request->selfChecked,
            'parents' => $request->parentsChecked,
            'spouse' => $request->spouseChecked,
            'other' => $request->otherChecked,
        ]);




        $who_will_pay = who_will_pay::create([
            'student_id' => $student->id,
            'name' => $request->payingName,
            'address' => $request->payingAddress,
            'address_official' => $request->payingOfficialAddress,
            'city' => $request->payingCity,
            'Province' => $request->payingState,
            'postal_code' => $request->payingPostalCode,
            'country' => $request->payingCountry,
            'phone_number' => $request->payingContactNumber,
            'email' => $request->payingEmail,
            'scholarship' => $request->hasScholarship,
        ]);


        $admin = admin_use::create([
            'student_id' => $student->id,
            'student_number' => $request->studentNumber,
            'total_fees' => $request->totalCourseFee,
            'registration_fees' => $request->registrationFee,
            'installment' => $request->installments,
            'discount' => $request->paymentDiscount,
            'join_date' => $request->joinDate,
            'end_date' => $request->endDate,
            'status' => $request->paymentStatus,
        ]);


        return response()->json(['message' => 'Student saved successfully', 'student' => $student], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error saving student', 'error' => $e->getMessage()], 500);
    }
    }


    public function show(Student $student) {}


    public function updateStudent(Request $request, $id)
    {
        // Find the student by ID
        // Fetch the student record based on the ID
        // return response()->json(['message'=>'xf','id',$id]);

       

        $student = Student::find($id);


        // Check if the student exists
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        // Format the date of birth
        $dateofmonth = "{$request->year}-{$request->month}-{$request->day}";

        // Update the student record
        $student->zoho_no = $request->zohonumber;
        $student->nic_number = $request->nic;
        $student->first_name = $request->studentFirstName;
        $student->middle_name = $request->studentMiddleName;
        $student->last_name = $request->studentLastName;
        $student->date_of_birth = $dateofmonth;
        $student->address = $request->streetAddress;
        $student->address_line = $request->streetAddressLine2;
        $student->city = $request->city;
        $student->province = $request->state;
        $student->postal_code = $request->postalCode;
        $student->country = $request->country;
        $student->email = $request->email;
        $student->phone_number = $request->phone;

        // Generate and update QR code
        $qrCodeContent = $student->id . '-' . $student->first_name;
        $qrCode = QrCode::format('png')->size(300)->generate($qrCodeContent);
        $base64QRCode = base64_encode($qrCode);
        $student->qr_code = 'data:image/png;base64,' . $base64QRCode;

        // Save the updated student data
        $student->save();

        // Generate QR Code and update it
        $qrCodeContent = $student->id . '-' . $student->first_name;
        $qrCode = QrCode::format('png')->size(300)->generate($qrCodeContent);
        $base64QRCode = base64_encode($qrCode);
        $student->qr_code = 'data:image/png;base64,' . $base64QRCode;
        $student->save();

        // Update emergency contact
        $emergency_contact = emergency_contact::where('student_id', $student->id)->first();
        if ($emergency_contact) {
            $emergency_contact->update([
                'first_name' => $request->emergencyFirstName,
                'last_name' => $request->emergencyLastName,
                'relationship' => $request->emergencyRelationship,
                'address' => $request->emergencyAddress,
                'address_line' => $request->emergencyStreetAddressLine2,
                'city' => $request->emergencyCity,
                'province' => $request->emergencyState,
                'postal_code' => $request->emergencyPostalCode,
                'email' => $request->emergencyEmail,
                'phone_number' => $request->emergencyPhoneNumber,
            ]);
        }

        // Update name_of_course
        $name_of_course = name_of_course::where('student_id', $student->id)->first();
        if ($name_of_course) {
            $name_of_course->update([
                'preferred_mode' => $request->appliedPreferredMode,
                'program_applied_for' => $request->programApplied,
                'course_name' => $request->appliedCourseName,
                'student_number' => $request->appliedStudentNumber,
            ]);
        }

        if ($request->hasFile('selectedOlFile')) {
            $file = $request->file('selectedOlFile');
            $olpath = $file->move(public_path('docs/documents'), $file->getClientOriginalName());
            $olpath = 'docs/documents/' . $file->getClientOriginalName();
        } else {
            $olpath = null;
        }

        if ($request->hasFile('selectedAlFile')) {
            $file = $request->file('selectedAlFile');
            $alpath = $file->move(public_path('docs/documents'), $file->getClientOriginalName());
            $alpath = 'docs/documents/' . $file->getClientOriginalName();
        } else {
            $alpath = null;
        }

        // Update qualifications
        $qualifications = qualifications::where('student_id', $student->id)->first();
        if ($qualifications) {
            $qualifications->update([
                'olexam' => $request->olExamName,
                'olpath' => $olpath,
                'alexam' => $request->alExamName,
                'alpath' => $alpath,
            ]);
        }

        // Update other_qualification
        $other_qualifications = other_qualifications::where('student_id', $student->id)->first();
        if ($other_qualifications) {
            $other_qualifications->update([
                'qualifications' => $request->examOrEmploymentName,
                'qualifications_line' => $request->qualificationsStatement,
            ]);
        }

        // Update applicant_checklist
        $applicant_checklist = applicant_checklist::where('student_id', $student->id)->first();
        if ($applicant_checklist) {
            $applicant_checklist->update([
                'newspaper' => $request->newsPapersAdvertisement,
                'seminar' => $request->seminarWebinar,
                'social_media' => $request->socialMedia,
                'open_events' => $request->openEvents,
                'bcas_website' => $request->bcasWebsite,
                'leaflets' => $request->leaflets,
                'student_review' => $request->studentReview,
                'radio' => $request->radio,
                'other' => $request->other,
            ]);
        }

        if ($request->hasFile('selectedImageFile')) {
            $file = $request->file('selectedImageFile');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;
            $file->move(public_path('docs/image'), $fileName);
            $image = 'docs/image/' . $fileName;
        } else {
            $image = null;
        }

        
        $student_image = student_image::where('student_id', $student->id)->first();

        if ($student_image) {
            
            $student_image->update([
                'image' => $image, 
            ]);
        } else {
           
            return response()->json(['error' => 'Student image not found'], 404);
        }

        $st_dob = $request->hasFile('selectedFile1') ? $this->handleFileUpload($request->file('selectedFile1'), 'docs/documets') : null;
        $st_nic = $request->hasFile('selectedFile2') ? $this->handleFileUpload($request->file('selectedFile2'), 'docs/documets') : null;

        // Update student_date_of_birth_certificate
        $student_date_of_birth_certificate = student_date_of_birth_certificate::where('student_id', $student->id)->first();
        if ($student_date_of_birth_certificate) {
            $student_date_of_birth_certificate->update([
                'date_of_birth_certificate' => $st_dob,
            ]);
        }

        // Update student_nic
        $student_nic = student_nic::where('student_id', $student->id)->first();
        if ($student_nic) {
            $student_nic->update([
                'nic' => $st_nic,
            ]);
        }

        // Update personal_statement
        $personal_statement = personal_statement::where('student_id', $student->id)->first();
        if ($personal_statement) {
            $personal_statement->update([
                'course_reason' => $request->courseReason,
                'self' => $request->selfChecked,
                'parents' => $request->parentsChecked,
                'spouse' => $request->spouseChecked,
                'other' => $request->otherChecked,
            ]);
        }

        // Update who_will_pay
        $who_will_pay = who_will_pay::where('student_id', $student->id)->first();
        if ($who_will_pay) {
            $who_will_pay->update([
                'name' => $request->payingName,
                'address' => $request->payingAddress,
                'address_official' => $request->payingOfficialAddress,
                'city' => $request->payingCity,
                'Province' => $request->payingState,
                'postal_code' => $request->payingPostalCode,
                'country' => $request->payingCountry,
                'phone_number' => $request->payingContactNumber,
                'email' => $request->payingEmail,
                'scholarship' => $request->hasScholarship,
            ]);
        }

        // Update admin_use
        $admin_use = admin_use::where('student_id', $student->id)->first();
        if ($admin_use) {
            $admin_use->update([
                'student_number' => $request->studentNumber,
                'total_fees' => $request->totalCourseFee,
                'registration_fees' => $request->registrationFee,
                'installment' => $request->installments,
                'discount' => $request->paymentDiscount,
                'join_date' => $request->joinDate,
                'end_date' => $request->endDate,
                'status' => 1,
            ]);
        }

        return response()->json(
            [
                'message' => 'Student updated successfully',
                'student' => $student,
                'emergency_contact' => $emergency_contact,
                'name_of_course' => $name_of_course,
                'qualifications' => $qualifications,
                'other_qualifications' => $other_qualifications,
                'applicant_checklist' =>  $applicant_checklist,
                'student_image' =>  $student_image,
                'student_date_of_birth_certificate' =>  $student_date_of_birth_certificate,
                'student_nic' => $student_nic,
                'personal_statement' => $personal_statement,
                'who_will_pay' => $who_will_pay,
                'admin_use' =>  $admin_use,


            ],
            200
        );
    }

    // private function handleFileUpload($file, $path)
    // {
    //     $fileName = time() . '.' . $file->getClientOriginalExtension();
    //     $file->move(public_path($path), $fileName);
    //     return $path . '/' . $fileName;
    // }




    public function digitalid(Request $request, Student $student)
    {
        $students = Student::with([
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
            'adminUse'
        ])->get();

        return response()->json(['students' => $students], 200);
    }




    public function destroy($id)
    {
        try {
            // Find the student with all related data
            $student = Student::with([
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
            ])->find($id);

            // If the student doesn't exist, return 404
            if (!$student) {
                return response()->json(['message' => 'Student not found'], 404);
            }

            // Start a database transaction
            DB::beginTransaction();

            // Delete related models
            $student->emergencyContact?->delete();
            $student->nameOfCourse?->delete();

            // Delete collections of related data
            $student->qualifications()->delete();
            $student->otherQualifications()->delete();

            $student->applicantChecklist?->delete();
            $student->studentImage?->delete();
            $student->studentDateOfBirthCertificate?->delete();
            $student->studentNic?->delete();
            $student->personalStatement?->delete();
            $student->whoWillPay?->delete();
            $student->adminUse?->delete();

            // Delete the student record itself
            $student->delete();

            // Commit the transaction
            DB::commit();

            return response()->json(['message' => 'Student and related data deleted successfully'], 200);
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            return response()->json(['message' => 'Error deleting student', 'error' => $e->getMessage()], 500);
        }
    }
}
