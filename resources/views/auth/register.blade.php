@extends('layouts.app')

@section('content')
<style>
    html, body {
        overflow: hidden;
        background-color: black;
    }

    .card {
        border: 1px solid #ffffff;
        border-radius: 1rem;
        background-color: black;
    }

    .form-control {
    background-color: #000; /* Pure black */
    color: #fff;             /* White text */
    border: 1px solid #444;
}

.form-control::placeholder {
    color: #888 !important;  /* Soft grey placeholder */
}

.form-control:focus {
    background-color: #000;  /* Black on focus */
    color: #fff;
    border-color: #fff;      /* White border */
    box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25); /* White glow */
}


    .btn-primary {
        background-color: #0d6efd;
        border: none;
    }

    .btn-primary:hover {
        background-color: #0b5ed7;
    }

    .text-muted {
        color: #bbb !important;
    }

    .alert {
        border-radius: 0.5rem;
    }

    a.text-white:hover {
        text-decoration: underline;
        color: #adb5bd;
    }
</style>


<div class="min-vh-100 d-flex justify-content-center align-items-center">
    <div class="card text-white shadow-lg w-100" style="max-width: 420px;">
        <div class="card-body p-4">
            <h2 class="card-title text-center mb-4">Register</h2>

            <!-- Alert Message -->
            <div id="alertContainer" class="alert d-none"></div>

            <form id="registerForm" class="needs-validation" novalidate>
                <div class="mb-3">
                    <input type="text" id="username" class="form-control form-control-lg" placeholder="Username" required>
                    <div class="invalid-feedback">Please enter a username</div>
                </div>

                <div class="mb-3">
                    <input type="email" id="email" class="form-control form-control-lg" placeholder="Email" required>
                    <div class="invalid-feedback">Please enter a valid email</div>
                </div>

                <div class="mb-4">
                    <input type="password" id="password" class="form-control form-control-lg" placeholder="Password" required>
                    <div class="invalid-feedback">Please enter a password</div>
                </div>

                <button type="submit" class="btn btn-light btn-lg w-100 mb-3">
                    <span class="submit-text">Register</span>
                    <span class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span>
                </button>

                <p class="text-center text-muted">
                    Already have an account?
                    <a href="/login" class="text-white">Login here</a>
                </p>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript Section -->
<script>
    document.getElementById("registerForm").addEventListener("submit", function (e) {
        e.preventDefault();

        const btn = this.querySelector('button[type="submit"]');
        const spinner = btn.querySelector('.spinner-border');
        const submitText = btn.querySelector('.submit-text');
        const alertDiv = document.getElementById('alertContainer');

        // Reset UI
        alertDiv.classList.add('d-none');
        btn.disabled = true;
        submitText.textContent = 'Registering...';
        spinner.classList.remove('d-none');

        axios.post("http://localhost:3000/api/auth/register", {
            username: document.getElementById("username").value.trim(),
            email: document.getElementById("email").value.trim().toLowerCase(),
            password: document.getElementById("password").value
        }).then(response => {
            showAlert('Registration successful! Redirecting...', 'success');
            setTimeout(() => {
                window.location.href = "/login";
            }, 1500);
        }).catch(err => {
            const errorMessage = err.response?.data?.message || 'Registration failed';
            showAlert(errorMessage, 'danger');
            document.getElementById("password").value = '';
        }).finally(() => {
            btn.disabled = false;
            submitText.textContent = 'Register';
            spinner.classList.add('d-none');
        });
    });

    function showAlert(message, type) {
        const alertDiv = document.getElementById('alertContainer');
        alertDiv.classList.remove('d-none', 'alert-success', 'alert-danger');
        alertDiv.classList.add(`alert-${type}`);
        alertDiv.textContent = message;
    }
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
