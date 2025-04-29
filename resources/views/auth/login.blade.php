@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow-md">
    <h2 class="text-2xl font-bold mb-4">Login</h2>
    <form id="loginForm">
        <input type="email" id="email" placeholder="Email" class="border p-2 w-full mb-2">
        <input type="password" id="password" placeholder="Password" class="border p-2 w-full mb-2">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 w-full">Login</button>
    </form>
    <p class="text-red-500 mt-2" id="loginError"></p>
</div>

<script>
document.getElementById("loginForm").addEventListener("submit", function(e) {
    e.preventDefault();
    axios.post("http://localhost:3000/api/auth/login", {
        email: document.getElementById("email").value,
        password: document.getElementById("password").value
    }).then(res => {
        localStorage.setItem("token", res.data.token);
        window.location.href = "/dashboard";
    }).catch(err => {
        document.getElementById("loginError").innerText = "Invalid credentials";
    });
});
</script>
@endsection
