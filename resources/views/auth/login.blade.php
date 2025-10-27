@extends('layouts.app')

@section('title', 'Login')

@section('content')

@if ($errors->has('login_error'))
    <div class="alert alert-danger">
        {{ $errors->first('login_error') }}
    </div>
@endif
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-sm" style="width: 400px;">
        <div class="card-body">
            <h3 class="card-title text-center mb-4">Login</h3>

            <div id="error-message" class="alert alert-danger d-none"></div>

            <form id="login-form" novalidate>
               @csrf
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input 
                        type="email" 
                        class="form-control" 
                        id="email" 
                        placeholder="Enter email" 
                        required 
                        autofocus
                    >
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input 
                        type="password" 
                        class="form-control" 
                        id="password" 
                        placeholder="Password" 
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>

            <p class="text-center mt-3">
                Don't have an account? <a href="{{ route('register') }}">Register</a>
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const errorBox = document.getElementById('error-message');
    const logoutBtn = document.getElementById('logout-btn');

    document.getElementById('login-form').addEventListener('submit', async function (e) {
        e.preventDefault();

        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();
        const csrfToken = document.querySelector('input[name="_token"]').value;

        errorBox.classList.add('d-none');
        errorBox.textContent = '';

        if (!email || !password) {
            errorBox.classList.remove('d-none');
            errorBox.textContent = 'Please enter both email and password.';
            return;
        }

        try {
            const response = await fetch('/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                credentials: 'include',
                body: JSON.stringify({ email, password })
            });

            const data = await response.json();

            if (!response.ok) {
                errorBox.classList.remove('d-none');

                if (data.errors) {
                    const firstError = Object.values(data.errors)[0][0];
                    errorBox.textContent = firstError;
                } else {
                    errorBox.textContent = data.message || 'Login failed. Please check your credentials.';
                }

                return;
            }

        
            if (data.status === 'weak_password') {
                alert(data.message); 
                window.location.href = data.redirect;
                return;
            }

            localStorage.setItem('token', data.token || '');
            localStorage.setItem('user', JSON.stringify(data.user));

         
            if (data.user.role === 'student') {
                window.location.href = '/student/courses';
            } else if (data.user.role === 'instructor') {
                window.location.href = '/instructor/dashboard';
            } else {
                window.location.href = '/';
            }

        } catch (error) {
            console.error('Login error:', error);
            errorBox.classList.remove('d-none');
            errorBox.textContent = 'Something went wrong. Please try again later.';
        }
    });

    if (logoutBtn) {
        logoutBtn.addEventListener('click', async () => {
            const token = localStorage.getItem('token');
            if (!token) {
                window.location.href = '/login';
                return;
            }

            try {
                await fetch('/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                    },
                    credentials: 'include',
                });
            } catch (error) {
                console.warn('Logout error:', error);
            }

            localStorage.removeItem('token');
            localStorage.removeItem('user');
            window.location.href = '/login';
        });
    }

 
    async function authFetch(url, options = {}) {
        const token = localStorage.getItem('token');
        if (!token) {
            window.location.href = '/login';
            throw new Error('No auth token');
        }

        const headers = options.headers || {};
        headers['Authorization'] = `Bearer ${token}`;
        headers['Accept'] = 'application/json';

        const response = await fetch(url, { ...options, headers });

        if (response.status === 401) {
            alert('Session expired. Please login again.');
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            window.location.href = '/login';
            throw new Error('Unauthorized');
        }

        return response;
    }
</script>


@endpush