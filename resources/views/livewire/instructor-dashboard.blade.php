<div class="container-fluid py-4 px-3 px-md-5">
    {{-- ===== Dashboard Header ===== --}}
    <div class="row mb-4 align-items-center text-center text-md-start">
        <div class="col-md-6 mb-3 mb-md-0">
            <h1 class="display-6 fw-bold text-primary">Instructor Dashboard</h1>
        </div>
        <div class="col-md-6 text-md-end">
            <small class="text-muted d-block">
                Summary as of {{ now()->format('d M Y, H:i') }}
            </small>
        </div>
    </div>

    {{-- ===== Summary Cards Row ===== --}}
    <div class="row g-4 mb-5">

        {{-- Top 3 Courses --}}
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 bg-light h-100">
                <div class="card-body">
                    <h6 class="card-title text-secondary fw-semibold">Top 3 Courses by Enrollment</h6>
                    @if($topCourses->isEmpty())
                        <p class="text-muted mt-3">No enrollments yet.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach ($topCourses as $course)
                                <li class="list-group-item bg-light border-0 d-flex justify-content-between px-0 py-2">
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
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 bg-light h-100 text-center">
                <div class="card-body">
                    <h6 class="card-title text-secondary fw-semibold">Total Revenue Generated</h6>
                    <div class="display-6 my-3 text-success fw-bold">
                        ${{ number_format($totalRevenue, 2) }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Students with more than 2 enrollments --}}
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm border-0 bg-light h-100">
                <div class="card-body">
                    <h6 class="card-title text-secondary fw-semibold">
                        Students Enrolled in More Than 2 Courses
                    </h6>

                    @if($studentsMultipleEnrollments->isEmpty())
                        <p class="text-muted mt-3">No students yet.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach ($studentsMultipleEnrollments as $student)
                                <li class="list-group-item bg-light border-0 px-0 py-2">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                                        <strong>{{ $student->name }}</strong>
                                        <button class="btn btn-sm btn-link text-decoration-none text-primary"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#courses-{{ $student->id }}">
                                            {{ $student->courses_count }} Courses
                                        </button>
                                    </div>

                                    <div class="collapse mt-2" id="courses-{{ $student->id }}">
                                        <ul class="ps-3 mb-0 small">
                                            @forelse ($studentCourses[$student->id] ?? [] as $course)
                                                <li>{{ $course->title }}</li>
                                            @empty
                                                <li><em>No course data found</em></li>
                                            @endforelse
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

    {{-- ===== Charts Section ===== --}}
    <div class="row g-5 mb-5">
        {{-- Top Courses Chart --}}
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm border-0 bg-light p-4 h-100">
                <h6 class="card-title text-secondary text-center mb-3 fw-semibold">
                    Top Courses by Enrollment
                </h6>
                <div class="chart-container" style="overflow-x:auto; width:100%; height:400px;">
                    <canvas id="CoursesChart" style="min-width:700px; height:400px;"></canvas>
                </div>
            </div>
        </div>

        {{-- Revenue per Student Chart --}}
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm border-0 bg-light p-4 h-100">
                <h6 class="card-title text-secondary text-center mb-3 fw-semibold">
                    Revenue per Student
                </h6>
                <div class="chart-container" style="overflow-x:auto; width:100%; height:400px;">
                    <canvas id="revenueChart" style="min-width:700px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Manage Courses Section ===== --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center text-center text-md-start">
                    <h5 class="mb-3 mb-md-0">Manage Your Courses</h5>
                    <a href="{{ route('instructor.courses.index') }}" class="btn btn-primary btn-lg">
                        Go to My Courses
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('livewire:load', () => {
    let topCoursesChart, revenueChart;

    window.addEventListener('renderCharts', (event) => {
        const { topCourses, revenue } = event.detail;

        if (topCoursesChart) topCoursesChart.destroy();
        if (revenueChart) revenueChart.destroy();

        const ctxTop = document.getElementById('CoursesChart').getContext('2d');
        topCoursesChart = new Chart(ctxTop, {
            type: 'bar',
            data: {
                labels: topCourses.labels,
                datasets: [{
                    label: 'Enrollments',
                    data: topCourses.data,
                    backgroundColor: topCourses.colors,
                    borderColor: topCourses.borders,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: ctx => `${ctx.label}: ${ctx.raw} students` } }
                },
                scales: { y: { beginAtZero: true } }
            }
        });

        const ctxRev = document.getElementById('revenueChart').getContext('2d');
        revenueChart = new Chart(ctxRev, {
            type: 'bar',
            data: {
                labels: revenue.labels,
                datasets: [{
                    label: 'Revenue ($)',
                    data: revenue.data,
                    backgroundColor: revenue.colors,
                    borderColor: revenue.borders,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: ctx => `${ctx.label}: $${ctx.raw}` } }
                },
                scales: { y: { beginAtZero: true } }
            }
        });
    });
});
</script>
@endpush
