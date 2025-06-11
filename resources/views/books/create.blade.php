@extends('layouts.app')

@section('content')
<style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        background-color: #000;
    }

    .min-vh-100 {
        min-height: 100vh;
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
        border-radius: 0.5rem;
        padding: 0.75rem;
        font-size: 1rem;
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

    .btn-light {
        background-color: #f8f9fa;
        border: none;
    }

    .btn-light:hover {
        background-color: #e2e6ea;
    }

    .alert-success {
        background-color: #28a745;
        color: #fff;
        text-align: center;
        margin-bottom: 15px;
    }
</style>

<div class="min-vh-100 d-flex justify-content-center align-items-center">
    <div class="card text-white shadow-lg w-100" style="max-width: 500px;">
        <div class="card-body p-4">
            <h2 class="card-title text-center mb-4">Add New Book</h2>

            <!-- Success Alert -->
            <div id="successAlert" class="alert alert-success d-none" role="alert">
                Book created successfully!
            </div>

            <form id="createBookForm">
                <div class="mb-3">
                    <input type="text" id="title" placeholder="Title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <input type="text" id="author" placeholder="Author" class="form-control" required>
                </div>
                <div class="mb-3">
                    <textarea id="description" placeholder="Description" class="form-control" rows="3" required></textarea>
                </div>
                <div class="mb-4">
                    <input type="number" id="year" placeholder="Year" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-light btn-lg w-100">
                    Add Book
                </button>
            </form>
        </div>
    </div>
</div>

<script>
const BACKEND_URL = "{{ env('BACKEND_URL', 'http://localhost:3000') }}";

document.getElementById("createBookForm").addEventListener("submit", function(e) {
    e.preventDefault();

    axios.post(BACKEND_URL + "/api/books", {
        title: document.getElementById("title").value,
        author: document.getElementById("author").value,
        description: document.getElementById("description").value,
        year: document.getElementById("year").value
    }, {
        headers: { Authorization: "Bearer " + localStorage.getItem("token") }
    }).then(() => {
        document.getElementById("successAlert").classList.remove("d-none");
        document.getElementById("createBookForm").reset();
        setTimeout(() => {
            window.location.href = "/dashboard";
        }, 2000);
    }).catch(err => {
        alert("Error adding book");
    });
});
</script>
@endsection
