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
            <h2 class="card-title text-center mb-4">Edit Book</h2>

            <!-- Success Alert -->
            <div id="successAlert" class="alert alert-success d-none" role="alert">
                Book updated successfully!
            </div>

            <form id="editBookForm">
                <div class="mb-3">
                    <input type="text" id="title" class="form-control" placeholder="Title" required>
                </div>
                <div class="mb-3">
                    <input type="text" id="author" class="form-control" placeholder="Author" required>
                </div>
                <div class="mb-3">
                <textarea id="description" placeholder="Description" class="form-control" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <input type="number" id="year" class="form-control" placeholder="Year" required>
                </div>
                <button type="submit" class="btn btn-light btn-lg w-100">
                    Update Book
                </button>
            </form>
        </div>
    </div>
</div>

<script>
const BACKEND_URL = "{{ env('BACKEND_URL', 'http://localhost:3000') }}";
const bookId = window.location.pathname.split("/").pop();

axios.get(`${BACKEND_URL}/api/books/${bookId}`, {
    headers: { Authorization: "Bearer " + localStorage.getItem("token") }
}).then(res => {
    const book = res.data;
    document.getElementById("title").value = book.title;
    document.getElementById("author").value = book.author;
    document.getElementById("description").value = book.description;
    document.getElementById("year").value = book.year;
});

document.getElementById("editBookForm").addEventListener("submit", function(e) {
    e.preventDefault();
    axios.put(`${BACKEND_URL}/api/books/${bookId}`, {
        title: document.getElementById("title").value,
        author: document.getElementById("author").value,
        description: document.getElementById("description").value,
        year: document.getElementById("year").value
    }, {
        headers: { Authorization: "Bearer " + localStorage.getItem("token") }
    }).then(() => {
        window.location.href = "/dashboard";
    }).catch(() => {
        alert("Failed to update book");
    });
});
</script>
@endsection
