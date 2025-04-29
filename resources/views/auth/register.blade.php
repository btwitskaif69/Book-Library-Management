@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow-md">
    <h2 class="text-2xl font-bold mb-4">Register</h2>
    <form id="registerForm">
        <input type="text" id="username" placeholder="Username" class="border p-2 w-full mb-2">
        <input type="email" id="email" placeholder="Email" class="border p-2 w-full mb-2">
        <input type="password" id="password" placeholder="Password" class="border p-2 w-full mb-2">
        <button type="submit" class="bg-green-500 text-white px-4 py-2 w-full">Register</button>
    </form>
    <p class="text-red-500 mt-2" id="registerError"></p>
</div>

<script>
document.getElementById("registerForm").addEventListener("submit", function(e) {
    e.preventDefault();
    axios.post("http://localhost:3000/api/auth/register", {
        username: document.getElementById("username").value,
        email: document.getElementById("email").value,
        password: document.getElementById("password").value
    }).then(() => {
        window.location.href = "/login";
    }).catch(err => {
        document.getElementById("registerError").innerText = "Registration failed";
    });
});
</script>
@endsection
