<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function toggle(Course $course)
    {
        $user = auth()->user();

        if ($user->wishedCourses()->where('course_id', $course->id)->exists()) {
            $user->wishedCourses()->detach($course->id);
            return response()->json(['status' => 'removed']);
        } else {
            $user->wishedCourses()->attach($course->id);
            return response()->json(['status' => 'added']);
        }
    }

public function index()
{
    $user = auth()->user();

    $courses = $user->wishedCourses()
                    ->orderBy('courses.id')
                    ->paginate(9);

    $enrolledCourseIds = $user->enrolledCourses()->pluck('courses.id')->toArray();

    return view('student.wishlist.index', compact('courses', 'enrolledCourseIds'));
}




}
