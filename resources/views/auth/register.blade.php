<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="card shadow-lg border-0 rounded-4" style="max-width: 400px; width: 100%;">
        <div class="card-body p-4">
            <h2 class="text-center text-primary fw-bold mb-4">Create an Account</h2>
            <form id="registerForm">
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" id="name" class="form-control" placeholder="Enter your full name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" class="form-control" placeholder="Enter your email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" class="form-control" placeholder="Enter your password" required>
                </div>
                <div class="mb-3">
                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                    <input type="password" id="confirmPassword" class="form-control" placeholder="Confirm your password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
                <p class="text-danger text-center mt-3" id="registerError"></p>
            </form>
            <p class="text-center mt-3">
                Already have an account? <a href="/login" class="text-primary text-decoration-none">Login here</a>
            </p>
        </div>
    </div>
</div>

<script>
document.getElementById("registerForm").addEventListener("submit", function(e) {
    e.preventDefault();
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;

    if (password !== confirmPassword) {
        document.getElementById("registerError").innerText = "Passwords do not match";
        return;
    }

    axios.post("http://localhost:3000/api/auth/register", {
        name: document.getElementById("name").value,
        email: document.getElementById("email").value,
        password: password
    }).then(res => {
        window.location.href = "/login";
    }).catch(err => {
        document.getElementById("registerError").innerText = "Registration failed. Please try again.";
    });
});
</script>
@endsection
</body>
</html>