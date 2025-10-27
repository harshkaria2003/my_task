<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Instructor\CourseController as InstructorCourseController;
use App\Http\Controllers\Student\CourseController as StudentCourseController;
use App\Http\Controllers\Instructor\DashboardController;
use App\Http\Controllers\PasswordController;
 use App\Models\PaymentReceipt;
 use App\http\controllers\Student\WishlistController;



Route::get('/home', function () {
    return redirect()->route('dashboard');
});


Route::get('/', function () {
    if (auth()->check()) {
      
        return redirect()->route('dashboard');
    }
    
    return redirect()->route('login');
});


Route::middleware('guest')->group(function () {
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});


Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('dashboard', function () {
        $role = auth()->user()->role; 

       
        $route = $role === 'instructor' ? 'instructor.dashboard' : 'student.courses.index';
        return redirect()->route($route);
    })->name('dashboard');


  
    Route::middleware('role:student')->prefix('student')->name('student.')->group(function () {
        Route::get('courses', [StudentCourseController::class, 'index'])->name('courses.index');
        Route::get('courses/{course}', [StudentCourseController::class, 'show'])->name('courses.show');
        Route::post('courses/{course}/enroll', [EnrollmentController::class, 'enroll'])->name('courses.enroll');
        Route::get('enrollments', [EnrollmentController::class, 'myEnrollments'])->name('enrollments');
        Route::get('checkout/{enrollment}', [PaymentController::class, 'checkout'])->name('checkout');
        Route::get('payment/success/{enrollment}', [PaymentController::class, 'success'])->name('payment.success');
        Route::get('payment/cancel/{enrollment}', [PaymentController::class, 'cancel'])->name('payment.cancel');
         Route::get('wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
         Route::post('wishlist/{course}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    });

   
    Route::middleware('role:instructor')->prefix('instructor')->name('instructor.')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('courses', [InstructorCourseController::class, 'index'])->name('courses.index');
        Route::get('courses/create', [InstructorCourseController::class, 'create'])->name('courses.create');
        Route::post('courses', [InstructorCourseController::class, 'store'])->name('courses.store');
        Route::get('courses/{course}/edit', [InstructorCourseController::class, 'edit'])->name('courses.edit');
        Route::put('courses/{course}', [InstructorCourseController::class, 'update'])->name('courses.update');
        Route::delete('courses/{course}', [InstructorCourseController::class, 'destroy'])->name('courses.destroy');
        Route::get('courses/{course}/enrollments', [InstructorCourseController::class, 'enrolledStudents'])->name('courses.enrolled_students');
    });
});


Route::get('/change-password', [PasswordController::class, 'showChangeForm'])->name('password.change');
Route::post('/change-password', [PasswordController::class, 'updatePassword'])->name('password.update');
Route::post('webhook/stripe', [PaymentController::class, 'handleWebhook']);


Route::get('student/payment/receipt/{enrollment}', [PaymentController::class, 'showReceipt'])
    ->name('student.payment.receipt');

Route::get('student/payment/receipt/{enrollment}/pdf', [PaymentController::class, 'downloadReceiptPdf'])
    ->name('student.payment.receipt.pdf');




Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/student/wishlist/{course}/toggle', [WishlistController::class, 'toggle'])->name('student.wishlist.toggle');
    Route::get('/student/wishlist', [WishlistController::class, 'index'])->name('student.wishlist.index');
});






