@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container py-5 d-flex justify-content-center align-items-center min-vh-100">
  <div class="row justify-content-center w-100">
    <div class="col-12 col-sm-10 col-md-8 col-lg-5">
      <div class="card shadow-sm border-0 rounded-4 p-4 p-md-5 bg-white">
        
        <h2 class="text-center mb-4 text-primary fw-bold">
          <i class="bi bi-box-arrow-in-right me-2"></i> Login
        </h2>

        <form id="login-form" novalidate>
          @csrf

       
          <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Email address</label>
            <input 
              type="email" 
              class="form-control form-control-lg" 
              id="email" 
              name="email"
              placeholder="Enter your email" 
              required 
              autofocus
            >
            <div class="invalid-feedback" id="email-error"></div>
          </div>
          <div class="mb-4">
            <label for="password" class="form-label fw-semibold">Password</label>
            <input 
              type="password" 
              class="form-control form-control-lg" 
              id="password" 
              name="password"
              placeholder="Enter your password" 
              required
            >
            <div class="invalid-feedback" id="password-error"></div>
          </div>

         
          <button type="submit" class="btn btn-success btn-lg w-100 py-2">
            <i class="bi bi-unlock me-2"></i> Login
          </button>
        </form>

        <p class="text-center mt-4 mb-0">
          Don’t have an account? 
          <a href="{{ route('register') }}" class="fw-semibold text-decoration-none">Register</a>
        </p>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.getElementById('login-form').addEventListener('submit', async function (e) {
  e.preventDefault();

  const emailInput = document.getElementById('email');
  const passwordInput = document.getElementById('password');
  const emailError = document.getElementById('email-error');
  const passwordError = document.getElementById('password-error');
  const csrfToken = document.querySelector('input[name="_token"]').value;

 
  [emailInput, passwordInput].forEach(input => input.classList.remove('is-invalid'));
  [emailError, passwordError].forEach(el => el.textContent = '');

  const email = emailInput.value.trim();
  const password = passwordInput.value.trim();

  if (!email || !password) {
    if (!email) {
      emailInput.classList.add('is-invalid');
      emailError.textContent = 'Email is required';
    }
    if (!password) {
      passwordInput.classList.add('is-invalid');
      passwordError.textContent = 'Password is required';
    }
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
      body: JSON.stringify({ email, password })
    });

    const data = await response.json();

    if (!response.ok) {
      if (data.errors) {
        if (data.errors.email) {
          emailInput.classList.add('is-invalid');
          emailError.textContent = data.errors.email[0];
        }
        if (data.errors.password) {
          passwordInput.classList.add('is-invalid');
          passwordError.textContent = data.errors.password[0];
        }
      } else if (data.message) {
        emailInput.classList.add('is-invalid');
        passwordInput.classList.add('is-invalid');
        passwordError.textContent = data.message;
      }
      return;
    }

    
    if (data.user.role === 'student') {
      window.location.href = '/student/courses';
    } else if (data.user.role === 'instructor') {
      window.location.href = '/instructor/dashboard';
    } else {
      window.location.href = '/';
    }

  } catch (error) {
    console.error('Login error:', error);
    passwordInput.classList.add('is-invalid');
    passwordError.textContent = 'Something went wrong. Please try again later.';
  }
});
</script>
@endpush
@endsection
