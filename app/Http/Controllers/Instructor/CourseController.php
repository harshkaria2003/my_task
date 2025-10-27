<?php
namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class CourseController extends Controller
{
    public function __construct() {
        $this->middleware(['auth', 'role:instructor']);
    }

    public function index() {
        $courses = Auth::user()->courses()->paginate(10);
        return view('instructor.courses.index', compact('courses'));
    }

    public function create() {
        return view('instructor.courses.create');
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ]);

        if ($request->hasFile('image')) {
          
            $path = $request->file('image')->store('courses', 'public');
            $validatedData['image'] = basename($path);
        }

        Auth::user()->courses()->create($validatedData);

        return redirect()->route('instructor.courses.index')->with('success', 'Course created successfully.');
    }

    public function edit(Course $course) {
        
        return view('instructor.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course) {
      

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ]);

        if ($request->hasFile('image')) {
          
            $path = $request->file('image')->store('courses', 'public');
            $validatedData['image'] = basename($path);

       
            if ($course->image && Storage::disk('public')->exists('courses/' . $course->image)) {
                Storage::disk('public')->delete('courses/' . $course->image);
            }
        }

        $course->update($validatedData);

        return redirect()->route('instructor.courses.index')->with('success', 'Course updated successfully.');
    }

    public function enrolledStudents($courseId)
    {
        $course = auth()->user()->courses()->findOrFail($courseId);

        $enrollments = \DB::table('enrollments')
            ->join('users as students', 'enrollments.student_id', '=', 'students.id')
            ->leftJoin('payments', 'enrollments.id', '=', 'payments.enrollment_id')
            ->where('enrollments.course_id', $course->id)
            ->select(
                'enrollments.id as enrollment_id',
                'students.id as student_id',
                'students.name as student_name',
                'students.email as student_email',
                'enrollments.payment_completed',
                'payments.payment_gateway',
                'payments.transaction_id',
                'payments.amount',
                'payments.status as payment_status',
                'payments.created_at as payment_date'
            )
            ->paginate(10);

        return view('instructor.courses.enrolled_students', compact('course', 'enrollments'));
    }

    public function destroy(Course $course)
    {
        if ($course->instructor_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

       
        if ($course->image && Storage::disk('public')->exists('courses/' . $course->image)) {
            Storage::disk('public')->delete('courses/' . $course->image);
        }

       $course->delete();

        return redirect()->route('instructor.courses.index')->with('success', 'Course deleted successfully.');
    }
}
