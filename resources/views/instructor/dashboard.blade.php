@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h1 class="display-5">Instructor Dashboard</h1>
        </div>
        <div class="col text-end">
            <small class="text-muted">Summary as of {{ now()->format('d M Y, H:i') }}</small>
        </div>
    </div>

    <div class="row g-3 mb-5">
        {{-- Top 3 Courses by Enrollment --}}
        <div class="col-lg-4 col-md-6">
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body">
                    <h6 class="card-title text-secondary">Top 3 Courses by Enrollment</h6>
                    @if($topCourses->isEmpty())
                        <p class="text-muted">No enrollments yet.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach ($topCourses as $course)
                                <li class="list-group-item d-flex justify-content-between bg-light border-0 px-0 py-2">
                                    <span>{{ $course->title }}</span>
                                    <span class="badge bg-primary">{{ $course->total_enrollments }} enrolled</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        {{-- Total Revenue --}}
        <div class="col-lg-4 col-md-6">
            <div class="card shadow-sm border-0 bg-light text-center">
                <div class="card-body">
                    <h6 class="card-title text-secondary">Total Revenue Generated</h6>
                    <div class="display-5 my-3 text-success">${{ number_format($totalRevenue, 2) }}</div>
                </div>
            </div>
        </div>

        {{-- Students with more than 2 enrollments --}}
        <div class="col-lg-4 col-md-12">
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body">
                    <h6 class="card-title text-secondary">Students Enrolled in more then 2 Courses</h6>
                    @if($studentsMultipleEnrollments->isEmpty())
                        <p class="text-muted">No students yet.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach ($studentsMultipleEnrollments as $student)
                                <li class="list-group-item bg-light border-0 px-0 py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong>{{ $student->name }}</strong>
                                        <button class="btn btn-sm btn-link text-decoration-none"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#courses-{{ $student->id }}"
                                                aria-expanded="false"
                                                aria-controls="courses-{{ $student->id }}">
                                            {{ $student->courses_count }} Courses
                                        </button>
                                    </div>

                                    <div class="collapse mt-2" id="courses-{{ $student->id }}">
                                        <ul class="ps-3 mb-0">
                                            @if(isset($studentCourses[$student->id]))
                                                @foreach ($studentCourses[$student->id] as $course)
                                                    <li>{{ $course->title }}</li>
                                                @endforeach
                                            @else
                                                <li><em>No course data found</em></li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

<div class="row mb-5">
    <div class="col-12">
        <div class="card shadow-sm border-0 bg-light p-4">
            <h6 class="card-title text-secondary">Top 3 Courses by Enrollment</h6>
            <canvas id="CoursesChart" height="100"></canvas>
        </div>
    </div>
</div>



<div class="row mb-5">
    <div class="col-12">
        <div class="card shadow-sm border-0 bg-light p-4">
            <h6 class="card-title text-secondary">Revenue per Student</h6>
            <canvas id="revenueChart" height="100"></canvas>
        </div>
    </div>
</div>  


<div class="row mb-5">
    <div class="col-12">
        <div class="card shadow-sm border-0 bg-light p-4">
            <h6 class="card-title text-secondary">Student Enrolled more then 3 courses </h6>
            <canvas id="enrolledcourses" height="100"></canvas>
        </div>
    </div>
</div> 



    {{-- Manage Your Courses Button --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Manage Your Courses</h5>
                    <a href="{{ route('instructor.courses.index') }}" class="btn btn-primary">
                        Go to My Courses
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



  @push('scripts')
  
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const instructorId = @json(auth()->id());

    if (typeof Echo !== 'undefined' && instructorId) {
        Echo.private(`instructor.${instructorId}`)
            .listen('.CourseEnrolled', function (e) {
                const message = `${e.student_name} enrolled in ${e.course_title} at ${e.enrolled_at}`;
                showToast(message);
            });
    }

    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-bg-success border-0 show position-fixed bottom-0 end-0 m-4 z-1055';
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 7000);
    }
</script>




<script>

const ctxTopCourses = document.getElementById('CoursesChart').getContext('2d');
new Chart(ctxTopCourses, {
    type: 'bar', 
    data: {
        labels: @json($topCourses->pluck('title')),
        datasets: [{
            label: 'Enrollments',
            data: @json($topCourses->pluck('total_enrollments')),
            backgroundColor: [
                'rgba(54, 162, 235, 0.6)',
                'rgba(255, 99, 132, 0.6)',
                'rgba(255, 206, 86, 0.6)'
            ],
            borderColor: [
                'rgba(54, 162, 235, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(255, 206, 86, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        plugins: {
            tooltip: {
                callbacks: {
                    label: ctx => `${ctx.label}: ${ctx.raw} students`
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
}); 


const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
new Chart(ctxRevenue, {
    type: 'bar',
    data: {
        labels: @json($revenuePerStudent->pluck('student_name')),
        datasets: [{
            label: 'Revenue ($)',
            data: @json($revenuePerStudent->pluck('total_spent')),
            backgroundColor: [
                'rgba(45, 20, 26, 1)',
                'rgba(27, 141, 216, 0.5)',
                'rgba(180, 106, 86, 0.8)',
                'rgba(71, 10, 10, 0.27)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 0.7)',
                'rgba(75, 192, 192, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        plugins: {
            tooltip: {
                callbacks: {
                    label: ctx => `${ctx.label}: $${ctx.raw}`
                }
            }
        }
    }
});



const ctxEnrolled = document.getElementById('enrolledcourses').getContext('2d');
new Chart(ctxEnrolled, {
    type: 'bar',
    data: {
        labels: @json($studentsMultipleEnrollments->pluck('student_id')), 
        datasets: [{
            label: 'Number of Courses',
            data: @json($studentsMultipleEnrollments->pluck('courses_count')),
            backgroundColor: [
                'rgba(45, 20, 26, 1)',
                'rgba(27, 141, 216, 0.5)',
                'rgba(180, 106, 86, 0.8)',
                'rgba(71, 10, 10, 0.27)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 0.7)',
                'rgba(75, 192, 192, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        plugins: {
            tooltip: {
                callbacks: {
                    label: ctx => `${ctx.label}: ${ctx.raw} courses`
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1}
            }
        }
    }
});
</script>





@endpush
