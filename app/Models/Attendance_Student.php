<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class Attendance_Student extends Model
{
    use HasFactory;

    protected $table = 'students';
    protected $fillable = [
        'zoho_no',
        'nic_no',
        'department',
        'batch',
        'first_name',
        'middle_name',
        'last_name',
        'date_of_birth',
        'address',
        'address_line',
        'city',
        'province',
        'postal_code',
        'country',
        'email',
        'phone_number',
        'user_id',
    ];

    public function emergencyContact()
    {
        return $this->hasOne(emergency_contact::class);
    }
    public function nameOfCourse()
    {
        return $this->hasOne(name_of_course::class);
    }
    public function qualifications()
    {
        return $this->hasOne(qualifications::class);
    }
    public function otherQualifications()
    {
        return $this->hasOne(other_qualifications::class);
    }
    public function applicantChecklist()
    {
        return $this->hasOne(applicant_checklist::class);
    }
    public function studentImage()
    {
        return $this->hasOne(student_image::class);
    }

    // Define the accessor for the 'image_url' property
    // public function getImageUrlAttribute()
    // {
    //     return $this->studentImage && $this->studentImage->image
    //         ? asset($this->studentImage->image)
    //         : asset('docs/image/no-image.png');
    // }

    public function studentDateOfBirthCertificate()
    {
        return $this->hasOne(student_date_of_birth_certificate::class);
    }
    public function studentNic()
    {
        return $this->hasOne(student_nic::class);
    }
    public function personalStatement()
    {
        return $this->hasOne(personal_statement::class);
    }
    public function whoWillPay()
    {
        return $this->hasOne(who_will_pay::class);
    }
    public function adminUse()
    {
        return $this->hasOne(admin_use::class);
    }
//department
    public function department()
    {
        return $this->belongsTo(departmentModel::class);
    }
    public function batch()
    {
        return $this->belongsTo(batch::class);
    }
    // Add relationship to User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Method to create associated user account
    public function createUserAccount($password = null)
    {
        // Generate password if not provided
        if (!$password) {
            $password = Str::random(10);
        }

        // Create user
        $user = User::create([
            'name' => $this->first_name . ' ' . $this->last_name,
            'email' => $this->email,
            'password' => Hash::make($password),
        ]);

        // Assign student role to user
        $studentRole = Role::where('slug', 'student')->first();
        if ($studentRole) {
            $user->roles()->attach($studentRole->id);
        }

        // Associate user with student
        $this->user_id = $user->id;
        $this->save();

        return [
            'user' => $user,
            'password' => $password // Return plain password for first-time communication
        ];
    }
}
