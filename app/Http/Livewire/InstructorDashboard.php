<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InstructorDashboard extends Component
{
    public $topCourses;
    public $topCoursesColors = [];
    public $topCoursesBorderColors = [];

    public $revenuePerStudent;
    public $revenueColors = [];
    public $revenueBorderColors = [];

    public $totalRevenue;
    public $studentsMultipleEnrollments;
    public $studentCourses = [];

    public function mount()
    {
        $instructorId = Auth::id();

      
        $this->topCourses = DB::table('courses')
            ->join('enrollments', 'courses.id', '=', 'enrollments.course_id')
            ->where('courses.instructor_id', $instructorId)
            ->select(
                'courses.id',
                'courses.title',
                DB::raw('COUNT(enrollments.id) as total_enrollments')
            )
            ->groupBy('courses.id', 'courses.title')
            ->limit(3)
            ->get();

        $this->topCoursesColors = $this->topCourses->map(fn($c) => 'rgba(15, 1, 1, 0.41)')->toArray();
        $this->topCoursesBorderColors = $this->topCourses->map(fn($c) => 'rgba(14, 36, 50, 1)')->toArray();


        $this->revenuePerStudent = DB::table('enrollments')
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->join('students', 'enrollments.student_id', '=', 'students.id')
            ->where('courses.instructor_id', $instructorId)
            ->select(
                'students.id as student_id',
                'students.name as student_name',
                DB::raw('SUM(enrollments.price) as total_spent')
            )
            ->groupBy('students.id', 'students.name')
            ->get();

        $this->revenueColors = $this->revenuePerStudent->map(fn($s) => 'rgba(75, 192, 192, 0.6)')->toArray();
        $this->revenueBorderColors = $this->revenuePerStudent->map(fn($s) => 'rgba(75, 192, 192, 1)')->toArray();

        
        $this->totalRevenue = $this->revenuePerStudent->sum('total_spent');

    
        $this->studentsMultipleEnrollments = DB::table('students')
            ->join('enrollments', 'students.id', '=', 'enrollments.student_id')
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->where('courses.instructor_id', $instructorId)
            ->select(
                'students.id',
                'students.name',
                DB::raw('COUNT(DISTINCT enrollments.course_id) as courses_count')
            )
            ->groupBy('students.id', 'students.name')
            ->having('courses_count', '>', 2)
            ->get();

        foreach ($this->studentsMultipleEnrollments as $student) {
            $this->studentCourses[$student->id] = DB::table('courses')
                ->join('enrollments', 'courses.id', '=', 'enrollments.course_id')
                ->where('enrollments.student_id', $student->id)
                ->where('courses.instructor_id', $instructorId)
                ->select('courses.title')
                ->get();
        }

      
        $this->dispatchBrowserEvent('renderCharts', [
            'topCourses' => [
                'labels' => $this->topCourses->pluck('title'),
                'data' => $this->topCourses->pluck('total_enrollments'),
                'colors' => $this->topCoursesColors,
                'borders' => $this->topCoursesBorderColors,
            ],
            'revenue' => [
                'labels' => $this->revenuePerStudent->pluck('student_name'),
                'data' => $this->revenuePerStudent->pluck('total_spent'),
                'colors' => $this->revenueColors,
                'borders' => $this->revenueBorderColors,
            ],
        ]);
    }

    public function render()
    {
        return view('livewire.instructor-dashboard');
    }
}
