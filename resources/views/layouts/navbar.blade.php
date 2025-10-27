<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">CourseApp</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto">
                @auth
                    @if(auth()->user()->hasRole('instructor'))
                        <li class="nav-item"><a href="{{ route('instructor.courses.index') }}" class="nav-link">My Courses</a></li>
                        <li class="nav-item"><a href="{{ route('instructor.notifications') }}" class="nav-link">Notifications</a></li>
                    @elseif(auth()->user()->hasRole('student'))
                        <li class="nav-item"><a href="{{ route('student.courses.index') }}" class="nav-link">Browse Courses</a></li>
                        <li class="nav-item"><a href="{{ route('student.enrollments') }}" class="nav-link">My Enrollments</a></li>
                    @endif

                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-link nav-link" type="submit">Logout</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item"><a href="{{ route('login') }}" class="nav-link">Login</a></li>
                    <li class="nav-item"><a href="{{ route('register') }}" class="nav-link">Register</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
