@extends('layouts.app')

@section('content')
<style>
    html, body {
        background-color: #000;
    }

    .card {
        background-color: black;
        border: 1px solid #fff;
        border-radius: 1rem;
    }

    .form-control {
        background-color: #000;
        color: #fff;
        border: 1px solid #444;
    }

    .form-control::placeholder {
        color: #888 !important;
    }

    .form-control:focus {
        background-color: #000;
        color: #fff;
        border-color: #fff;
        box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25);
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
            <h2 class="card-title text-center mb-4">Login</h2>

            <p class="text-danger text-center" id="loginError"></p>

            <form id="loginForm" class="needs-validation" novalidate>
                <div class="mb-3">
                    <input type="email" id="email" class="form-control form-control-lg" placeholder="Email" required>
                    <div class="invalid-feedback">Please enter your email</div>
                </div>

                <div class="mb-4">
                    <input type="password" id="password" class="form-control form-control-lg" placeholder="Password" required>
                    <div class="invalid-feedback">Please enter your password</div>
                </div>

                <button type="submit" class="btn btn-light btn-lg w-100 mb-3">
                    <span class="submit-text">Login</span>
                    <span class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span>
                </button>

                <p class="text-center text-muted">
                    Don't have an account?
                    <a href="/register" class="text-white">Register here</a>
                </p>
            </form>
        </div>
    </div>
</div>

<script>
const BACKEND_URL = "{{ env('BACKEND_URL', 'http://localhost:3000') }}";

document.getElementById("loginForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const btn = this.querySelector('button[type="submit"]');
    const spinner = btn.querySelector('.spinner-border');
    const submitText = btn.querySelector('.submit-text');
    const errorText = document.getElementById("loginError");

    // Reset UI
    errorText.innerText = '';
    btn.disabled = true;
    submitText.textContent = 'Logging in...';
    spinner.classList.remove('d-none');

    axios.post(BACKEND_URL + "/api/auth/login", {
        email: document.getElementById("email").value,
        password: document.getElementById("password").value
    }).then(res => {
        localStorage.setItem("token", res.data.token);
        window.location.href = "/dashboard";
    }).catch(err => {
        errorText.innerText = "Invalid credentials";
    }).finally(() => {
        btn.disabled = false;
        submitText.textContent = 'Login';
        spinner.classList.add('d-none');
    });
});
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
