<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    
public function index()
{
    $instructorId = Auth::id();

  
    $topCourses = DB::table('courses')
        ->join('enrollments', 'courses.id', '=', 'enrollments.course_id')
        ->where('courses.instructor_id', $instructorId)
        ->select('courses.id', 'courses.title', DB::raw('COUNT(enrollments.id) as total_enrollments'))
        ->groupBy('courses.id', 'courses.title')
        ->limit(3)
        ->get();

 
    $totalRevenue = DB::table('payments')
        ->join('enrollments', 'payments.enrollment_id', '=', 'enrollments.id')
        ->join('courses', 'enrollments.course_id', '=', 'courses.id')
        ->where('courses.instructor_id', $instructorId)
        ->sum('payments.amount');

  
    $studentsMultipleEnrollments = DB::table('enrollments')
        ->join('courses', 'enrollments.course_id', '=', 'courses.id')
        ->join('users', 'enrollments.student_id', '=', 'users.id')
        ->where('courses.instructor_id', $instructorId)
        ->select('users.id', 'users.name', DB::raw('COUNT(DISTINCT enrollments.course_id) as courses_count'))
        ->groupBy('users.id', 'users.name')
        ->havingRaw('COUNT(DISTINCT enrollments.course_id) > 2')
        ->get();

   
    $studentIds = $studentsMultipleEnrollments->pluck('id');

    $studentCourses = DB::table('enrollments')
        ->join('courses', 'enrollments.course_id', '=', 'courses.id')
        ->whereIn('enrollments.student_id', $studentIds)
        ->where('courses.instructor_id', $instructorId)
        ->select('enrollments.student_id', 'courses.title')
        ->get()
        ->groupBy('student_id');

   
    $revenuePerStudent = DB::table('payments')
        ->join('enrollments', 'payments.enrollment_id', '=', 'enrollments.id')
        ->join('courses', 'enrollments.course_id', '=', 'courses.id')
        ->join('users', 'enrollments.student_id', '=', 'users.id')
        ->where('courses.instructor_id', $instructorId)
        ->select(
            'users.name as student_name',
            DB::raw('SUM(payments.amount) as total_spent')
        )
        ->groupBy('users.name')
        ->orderByDesc('total_spent')
        ->get();


$topCoursesColors = $topCourses->map(fn($c) => 'rgba(15, 1, 1, 0.41)')->toArray();
$topCoursesBorderColors = $topCourses->map(fn($c) => 'rgba(14, 36, 50, 1)')->toArray();


$revenueColors = $revenuePerStudent->map(fn($s) => 'rgba(75, 192, 192, 0.6)')->toArray();
$revenueBorderColors = $revenuePerStudent->map(fn($s) => 'rgba(75, 192, 192, 1)')->toArray();

   return view('instructor.dashboard', compact(
    'topCourses',
    'totalRevenue',
    'studentsMultipleEnrollments',
    'studentCourses',
    'revenuePerStudent',
    'topCoursesColors',
    'topCoursesBorderColors',
    'revenueColors',
    'revenueBorderColors'
));

}


}
