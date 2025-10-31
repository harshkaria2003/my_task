<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Register</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

  <style>
   
    body {
      background: linear-gradient(135deg, #f8f9fa, #e9ecef);
      min-height: 100vh;
    }
    .card {
      border-radius: 1rem;
    }
    @media (max-width: 576px) {
      .card {
        padding: 1.5rem !important;
      }
      h2 {
        font-size: 1.5rem;
      }
    }
  </style>
</head>
<body class="d-flex align-items-center justify-content-center">

  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-12 col-sm-10 col-md-8 col-lg-5"> 
        <div class="card shadow-sm p-4 p-md-5 bg-white">
          <h2 class="mb-4 text-center text-primary fw-bold">Create Your Account</h2>

          <form id="registerForm" novalidate>
            @csrf

          
            <div class="mb-3">
              <label for="name" class="form-label fw-semibold">Full Name</label>
              <input type="text" class="form-control form-control-lg" id="name" name="name" placeholder="John Doe" required autofocus>
              <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
              <label for="email" class="form-label fw-semibold">Email Address</label>
              <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="example@email.com" required>
              <div class="invalid-feedback"></div>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label fw-semibold">Password</label>
              <input type="password" class="form-control form-control-lg" id="password" name="password" required placeholder="********">
              <div class="form-text text-muted">
                Password must be at least 8 characters long.
              </div>
              <div class="invalid-feedback"></div>
            </div>

           
            <div class="mb-3">
              <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
              <input type="password" class="form-control form-control-lg" id="password_confirmation" name="password_confirmation" required placeholder="********">
              <div class="invalid-feedback"></div>
            </div>

          
            <div class="mb-4">
              <label for="role" class="form-label fw-semibold">Select Role</label>
              <select class="form-select form-select-lg" id="role" name="role" required>
                <option value="" disabled selected>Select Role</option>
                <option value="student">Student</option>
                <option value="instructor">Instructor</option>
              </select>
              <div class="invalid-feedback"></div>
            </div>

        
            <button type="submit" class="btn btn-success btn-lg w-100 py-2">
              <i class="bi bi-person-plus me-2"></i> Register
            </button>
          </form>

          <p class="mt-4 text-center mb-0">
            Already have an account? <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Login</a>
          </p>
        </div>
      </div>
    </div>
  </div>

  <script>
  document.getElementById('registerForm').addEventListener('submit', async function(event) {
      event.preventDefault();

      const form = this;
      form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
      form.querySelectorAll('.invalid-feedback').forEach(el => el.innerText = '');

      const formData = {
          name: form.name.value.trim(),
          email: form.email.value.trim(),
          password: form.password.value,
          password_confirmation: form.password_confirmation.value,
          role: form.role.value
      };

      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      try {
          const response = await fetch('/register', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
                  'Accept': 'application/json',
                  'X-CSRF-TOKEN': csrfToken,
              },
              body: JSON.stringify(formData)
          });

          const data = await response.json();

          if (!response.ok) {
              if (data.errors) {
                  for (const [field, messages] of Object.entries(data.errors)) {
                      const input = form.querySelector(`[name="${field}"]`);
                      if (input) {
                          input.classList.add('is-invalid');
                          const feedback = input.parentNode.querySelector('.invalid-feedback');
                          feedback.innerText = messages.join(' ');
                      }
                  }
              } else if (data.message) {
                  alert(data.message);
              }
              return;
          }

          alert(data.message);
          window.location.href = data.redirect;

      } catch (error) {
          alert('Network error. Please try again.');
          console.error(error);
      }
  });
  </script>
</body>
</html>
