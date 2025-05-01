<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliotheca | Book Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .book-card {
            perspective: 1000px;
            transition: transform 0.3s ease;
        }

        .book-cover {
            transform-style: preserve-3d;
            transform: rotateY(-5deg);
            transition: all 0.3s ease;
        }

        .book-card:hover .book-cover {
            transform: rotateY(-10deg) rotateX(5deg) translateY(-5px);
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
@extends('layouts.app')

@section('content')
<div class="max-w-8xl mx-auto px-4 py-12">
    <!-- Header -->
    <div class="container py-5">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4">
            <div>
                <h1 class="display-4 fw-bold text-primary">Bibliotheca</h1>
                <p class="text-muted">Your personal literary universe</p>
            </div>
            <div class="d-flex flex-column flex-sm-row gap-3 mt-3 mt-lg-0">
                <div class="input-group">
                    <span class="input-group-text bg-light border-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-0 shadow-sm" placeholder="Search books...">
                </div>
                <a href="/books/create" class="btn btn-primary shadow-sm">
                    <i class="fas fa-plus me-2"></i>Add Book
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Book Grid -->
        <div class="flex-1">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h4 fw-bold text-secondary">Your Collection</h2>
                    <button class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-th-large"></i>
                    </button>
                </div>
                <div id="bookList" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                    <!-- Books will be dynamically loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Book Detail Modal -->
<div id="bookModal" class="modal fade" tabindex="-1" aria-labelledby="bookModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modalTitle" class="modal-title text-primary"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    <!-- Book Image -->
                    <div class="col-md-6">
                        <img id="modalImage" src="" alt="Book Cover" class="img-fluid rounded shadow-sm"
                             onerror="this.onerror=null; this.src='https://via.placeholder.com/200x300?text=No+Image';">
                    </div>
                    <!-- Book Details -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Author</label>
                            <p id="modalAuthor" class="fw-bold"></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Year</label>
                            <p id="modalYear" class="fw-bold"></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Description</label>
                            <p id="modalDescription"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const config = {
    apiBaseUrl: "http://localhost:3000/api/books",
    token: localStorage.getItem("token")
};

let allBooks = [];

// Helpers
const getInitials = (title) => 
    title.split(' ').map(word => word[0]).join('').substring(0, 3).toUpperCase();

const renderBookCard = (book, index) => `
    <div class="col">
        <div class="card h-100 shadow-sm border-0">
            <img src="${book.image}" 
                 alt="${book.title}" 
                 class="card-img-top rounded-top"
                 onerror="this.onerror=null; this.src='https://via.placeholder.com/200x300?text=No+Image';">
            <div class="card-body d-flex flex-column">
                <h5 class="card-title text-truncate">${book.title}</h5>
                <p class="card-text text-muted small mb-1">${book.author}</p>
                <p class="card-text text-muted small">${book.year}</p>
                <p class="card-text text-truncate text-muted small">${book.description || ''}</p>
                <div class="mt-auto d-flex gap-2">
                    <button class="btn btn-outline-primary btn-sm" onclick="showBookModal('${book._id}')">
                        <i class="fas fa-eye"></i> View
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="editBook('${book._id}')">
                        <i class="fas fa-pencil-alt"></i> Edit
                    </button>
                    <button class="btn btn-outline-danger btn-sm" onclick="deleteBook('${book._id}')">
                        <i class="fas fa-trash-alt"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>`;

const showBookModal = (bookId) => {
    const book = allBooks.find(b => b._id === bookId);
    if (!book) return;

    // Populate modal content
    document.getElementById('modalTitle').textContent = book.title;
    document.getElementById('modalImage').src = book.image;
    document.getElementById('modalAuthor').textContent = book.author;
    document.getElementById('modalYear').textContent = book.year;
    document.getElementById('modalDescription').textContent = book.description || 'No description available';

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('bookModal'));
    modal.show();
};

const closeModal = () => {
    const modal = bootstrap.Modal.getInstance(document.getElementById('bookModal'));
    modal.hide();
};

const fetchBooks = async () => {
    try {
        const { data } = await axios.get(config.apiBaseUrl, {
            headers: { Authorization: `Bearer ${config.token}` }
        });
        allBooks = data;
        document.getElementById('bookList').innerHTML = 
            allBooks.map((book, i) => renderBookCard(book, i)).join('');
    } catch (error) {
        console.error("Error loading books:", error);
        alert("Failed to load books");
    }
};

document.addEventListener('DOMContentLoaded', () => {
    fetchBooks();
});
</script>
@endsection
</body>
</html>