<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center min-vh-100">
  <div class="card shadow-sm p-4" style="max-width: 450px; width: 100%;">
    <h2 class="mb-4 text-center">Register</h2>

    <div id="alert-container"></div>

    <form id="registerForm">
      @csrf
      <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" required autofocus>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>

      <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
      </div>

      <div class="mb-4">
        <label for="role" class="form-label">Select Role</label>
        <select class="form-select" id="role" name="role" required>
          <option value="" disabled selected>Select Role</option>
          <option value="student">Student</option>
          <option value="instructor">Instructor</option>
        </select>
      </div>

      <button type="submit" class="btn btn-success w-100">Register</button>
    </form>

    <p class="mt-3 text-center">
      Already have an account? <a href="{{ route('login') }}">Login</a>
    </p>
  </div>
</div>

<script>
  document.getElementById('registerForm').addEventListener('submit', async function(event) {
    event.preventDefault();

    const alertContainer = document.getElementById('alert-container');
    alertContainer.innerHTML = '';

    const formData = {
        name: this.name.value.trim(),
        email: this.email.value.trim(),
        password: this.password.value,
        password_confirmation: this.password_confirmation.value,
        role: this.role.value
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
                const errorList = Object.values(data.errors).flat();
                alertContainer.innerHTML = `
                    <div class="alert alert-danger">
                        <ul>${errorList.map(err => `<li>${err}</li>`).join('')}</ul>
                    </div>`;
            } else if (data.message) {
                alertContainer.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
            } else {
                alertContainer.innerHTML = `<div class="alert alert-danger">An unknown error occurred.</div>`;
            }
            return;
        }

        alertContainer.innerHTML = `<div class="alert alert-success">${data.message} Redirecting...</div>`;

        setTimeout(() => {
            window.location.href = data.redirect;
        }, 2000);

    } catch (error) {
        alertContainer.innerHTML = `<div class="alert alert-danger">Network error. Please try again.</div>`;
        console.error(error);
    }
});

</script>

</body>
</html>
