<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\ExpenseTypeController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\LecturehallController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\SlotController;
use App\Http\Controllers\AssigntomoduleController;
use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\Attendance_OtpController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Attendance_StudentTimetableController;
use App\Http\Controllers\Attendance_SlotController;
use App\Http\Controllers\NotificationController;

use App\Http\Controllers\TimetableController;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\StudentController;


use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaymentController;

Route::get('/notification', [NotificationController::class, 'checkNotification']);


Route::get('/view', [PersonController::class, 'getAll']);
Route::post('/create', [PersonController::class, 'store']);
Route::get('/view/{id}', [PersonController::class, 'show']);
Route::put('/update/{id}', [PersonController::class, 'update']);
Route::delete('/delete/{id}', [PersonController::class, 'destroy']);


Route::get('/viewm', [ModuleController::class, 'getAll']);
Route::post('/createm', [ModuleController::class, 'store']);
Route::get('/viewm/{id}', [ModuleController::class, 'show']);
Route::put('/updatem/{id}', [ModuleController::class, 'update']);
Route::delete('/deletem/{id}', [ModuleController::class, 'destroy']);


Route::get('/viewlh', [LecturehallController::class, 'getAll']);
Route::post('/createlh', [LecturehallController::class, 'store']);
Route::get('/viewlh/{id}', [LecturehallController::class, 'show']);
Route::put('/updatelh/{id}', [LecturehallController::class, 'update']);
Route::delete('/deletelh/{id}', [LecturehallController::class, 'destroy']);


Route::get('/viewl', [LectureController::class, 'getAll']);
Route::post('/createl', [LectureController::class, 'store']);
Route::get('/viewl/{id}', [LectureController::class, 'show']);
Route::put('/updatel/{id}', [LectureController::class, 'update']);
Route::delete('/deletel/{id}', [LectureController::class, 'destroy']);


Route::get('/batches',[BatchController::class,'showall']);
Route::get('/batches/{id}',[BatchController::class,'showbyid']);
Route::post('/batches',[BatchController::class,'create']);
Route::put('/batches/{id}', [BatchController::class, 'update']); // Update
Route::delete('/batches/{id}', [BatchController::class, 'destroy']); // Delete

Route::get('/slots', [SlotController::class, 'index']);
Route::get('/slots/{id}', [SlotController::class, 'show']);
Route::post('/slots', [SlotController::class, 'store']);
Route::put('/slots/{id}', [SlotController::class, 'update']);
Route::delete('/slots/{id}', [SlotController::class, 'destroy']);



Route::get('/timetable', [TimetableController::class, 'view']);
Route::post('/timetable', [TimetableController::class, 'store']);
Route::put('/timetable/{id}', [TimetableController::class, 'update']);

Route::get('/assign', [AssigntomoduleController::class, 'getAll']);
Route::get('/assign/{id}', [AssigntomoduleController::class, 'show']);
Route::post('/assign', [AssigntomoduleController::class, 'store']);
Route::put('/assign/{id}', [AssigntomoduleController::class, 'update']);
Route::delete('/assign/{id}', [AssigntomoduleController::class, 'destroy']);


//thileepan
Route::post('/generate-otp', [Attendance_OtpController::class, 'create']);
Route::post('/verify-otp', [AttendanceController::class, 'verifyOtp']);
Route::get('/verify-qr', [AttendanceController::class, 'verifyQr']);
Route::get('/student/{studentId}/timetable', [Attendance_StudentTimetableController::class, 'getStudentTimetable']);
Route::get('/Slot', [Attendance_SlotController::class, 'getAllSlot']);

//group d 
Route::post('/save-bank-account',[BankAccountController::class,'store']);
Route::post('/save-expense-type',[ExpenseTypeController::class,'store']);
Route::get('/expenseTypes',[ExpenseTypeController::class,'index']);
Route::get('/banks',[BankAccountController::class,'index']);


Route::post('/login', [AuthController::class, 'login']);



Route::get('/departmentm/{department_id}', [ModuleController::class, 'filterByDepartment']);


//group c 
Route::get('/getstudents',[StudentController::class,'index']);
Route::post('/addStudent',[StudentController::class,'savestudents']);
Route::post('/putstudent/{id}',[StudentController::class,'updateStudent']);
Route::delete('/students/{id}',[StudentController::class,'destroy']);
Route::get('/digitalid',[StudentController::class,'digitalid']);

Route::get('/dashboardDetails',[DashBoardController::class,'index']);
Route::post('/Oauth/login',[LoginController::class,'login']);


Route::prefix('payment')->group(function () {

    Route::get('/getStudentDetails/{id}',[PaymentController::class,'search']);
    Route::post('/payPayment',[PaymentController::class,'payPayment']);

});

