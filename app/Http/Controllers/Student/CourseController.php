<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class CourseController extends Controller
{
    public function __construct() {
        $this->middleware(['auth', 'role:student']);
    }
public function index(Request $request)
{
    $user = Auth::user();
    $enrolledCourseIds = $user->enrollments()->pluck('course_id')->toArray();

    $query = Course::query()->with('instructor'); 

 
    if ($request->query('filter') === 'my') {
        $query->whereIn('id', $enrolledCourseIds);
    }

  
    if ($request->filled('min_price')) {
        $query->where('price', '>=', $request->min_price);
    }
    if ($request->filled('max_price')) {
        $query->where('price', '<=', $request->max_price);
    }


    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', '%' . $search . '%')
              ->orWhereHas('instructor', function ($q2) use ($search) {
                  $q2->where('name', 'like', '%' . $search . '%');
              });
        });
    }

    $courses = $query->paginate(9)->appends($request->query());

    return view('student.courses.index', compact('courses', 'enrolledCourseIds'));
}


 
    public function show(Course $course)
    {
        $user = Auth::user();

        $enrolled = $course->enrollments()
            ->where('student_id', $user->id)
            ->exists();

        return view('student.courses.show', compact('course', 'enrolled'));
    }
}
