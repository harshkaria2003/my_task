<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Instructor\CourseController as InstructorCourseController;
use App\Http\Controllers\Student\CourseController as StudentCourseController;


Route::post('register', [AuthController::class, 'apiRegister']);
Route::post('login', [AuthController::class, 'apiLogin']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'apiLogout']);


    Route::middleware('role:student')->prefix('student')->group(function () {
        Route::get('courses', [StudentCourseController::class, 'index']);
        Route::get('courses/{course}', [StudentCourseController::class, 'show']);
        Route::post('courses/{course}/enroll', [EnrollmentController::class, 'enroll']);
        Route::get('enrollments', [EnrollmentController::class, 'myEnrollments']);
        Route::get('checkout/{enrollment}', [PaymentController::class, 'checkout']);
        Route::get('payment/success/{enrollment}', [PaymentController::class, 'success']);
        Route::get('payment/cancel/{enrollment}', [PaymentController::class, 'cancel']);
    });

  
    Route::middleware('role:instructor')->prefix('instructor')->group(function () {
        Route::get('dashboard', [InstructorCourseController::class, 'dashboard']);
        Route::get('courses', [InstructorCourseController::class, 'index']);
        Route::post('courses', [InstructorCourseController::class, 'store']);
        Route::get('courses/{course}/edit', [InstructorCourseController::class, 'edit']);
        Route::put('courses/{course}', [InstructorCourseController::class, 'update']);
        Route::delete('courses/{course}', [InstructorCourseController::class, 'destroy']);
        Route::get('courses/{course}/enrollments', [InstructorCourseController::class, 'enrolledStudents']);
    });
});


Route::post('/webhook/stripe', [PaymentController::class, 'handleWebhook']);
