<?php
namespace App\Http\Controllers;

use App\Events\CourseEnrolled;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    } 

 public function myEnrollments()
{
 $enrollments = Enrollment::with(['course', 'course.instructor'])
    ->where('student_id', auth()->id())
    ->paginate(9);

    return view('student.enrollments', compact('enrollments'));
}

public function enroll(Request $request, Course $course)
{
    $user = auth()->user();

    if ($user->role != 'student') {
        abort(403, 'Only students can enroll');
    }

    $enrollment = Enrollment::where('student_id', $user->id)
                            ->where('course_id', $course->id)
                            ->first();

    if ($enrollment) {
        if (!$enrollment->payment_completed) {
            return redirect()->route('student.checkout', $enrollment->id);
        }

        return redirect()->route('student.enrollments')->with('info', 'You have already completed enrollment and payment.');
    }

    $enrollment = Enrollment::create([
        'course_id' => $course->id,
        'student_id' => $user->id,
        'payment_completed' => false,
    ]);

    $enrollment->load('student', 'course');

    event(new CourseEnrolled($enrollment));

    return redirect()->route('student.checkout', $enrollment->id)
                     ->with('success', 'Enrolled! Please complete payment.');
}


}
