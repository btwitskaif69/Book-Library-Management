<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LibraryHub | Book Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
    body {
        font-family: 'Montserrat', sans-serif;
        background: yellow; /* Set background to black */
        color: #f5f5f5; /* Light text color for contrast */
        min-height: 100vh;
        margin: 0;
    }

    .container {
        padding: 3rem 2rem;
    }


    .header p {
        color: #bdc3c7;
        margin-top: -10px;
    }

    .book-card {
        transition: all 0.3s ease;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        background: #2c3e50; /* Dark background for cards */
    }

    .book-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .book-cover {
        object-fit: cover;
        height: 300px;
        border-radius: 8px 8px 0 0;
    }

    .card-body {
        padding: 1.5rem;
    }

    .card-title {
        font-size: 1.1rem;
        color: #ecf0f1; /* Light text color for card title */
        font-weight: 600;
    }

    .card-text {
        color: #bdc3c7;
    }

    .btn {
        font-size: 0.875rem;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        transition: all 0.2s ease;
    }

    .btn:hover {
        opacity: 0.9;
        transform: scale(1.05);
    }

    .btn-outline-primary {
        color: #3498db;
        border: 1px solid #3498db;
    }

    .btn-outline-secondary {
        color: #2ecc71;
        border: 1px solid #2ecc71;
    }

    .btn-outline-danger {
        color: #e74c3c;
        border: 1px solid #e74c3c;
    }

    .modal-content {
        border-radius: 12px;
        background: #34495e; /* Dark background for the modal */
    }

    .modal-body p {
        color: #ecf0f1;
        font-size: 1rem;
        line-height: 1.6;
    }
    </style>
</head>
<body>
@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Header -->
    <div class="header text-center rounded" style="color: white; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);">
    <h1 class="display-4 fw-medium mb-4">
    <i class="bi bi-book"></i> LibraryHub
</h1>


    <p style="font-size: 1.1rem; color: #ecf0f1;">Your personal library, beautifully organized.</p>
</div>

        <div class="d-flex justify-content-center gap-3">
            <div class="input-group w-50">
                <span class="input-group-text bg-light border-0">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" id="searchInput" class="form-control border-0 shadow-sm" placeholder="Search books..." oninput="handleSearch()">
            </div>
            <a href="/books/create" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Book
            </a>
        </div>
    </div>

    <!-- Book Collection -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="bookList">
        <!-- Dynamically loaded books will appear here -->
    </div>
</div>

<!-- Book Detail Modal -->
<div id="bookModal" class="modal fade" tabindex="-1" aria-labelledby="bookModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header"  style="background-color: black; color: white;">
                <h5 id="modalTitle" class="modal-title"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="background-color: black; color: white;">
                <div class="row">
                    <div class="col-md-6">
                        <img id="modalImage" src="" alt="Book Cover" class="img-fluid rounded">
                    </div>
                    <div class="col-md-6">
                        <h6>Author</h6>
                        <p id="modalAuthor"></p>
                        <h6>Year</h6>
                        <p id="modalYear"></p>
                        <h6>Description</h6>
                        <p id="modalDescription"></p>
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

const renderBookCard = (book) => `
    <div class="col">
        <div class="card book-card" style="background-color: #000; color: #fff; border: 1px solid #fff; border-radius: 8px; box-shadow: 0 4px 12px rgba(255, 255, 255, 0.1);">

            <img src="${book.image}" class="book-cover" alt="${book.title}" onerror="this.onerror=null; this.src='https://via.placeholder.com/200x300?text=No+Image';">
            <div class="card-body">
                <h5 class="card-title fs-3 mb-4 text-truncate">${book.title}</h5>
                <p class="card-text fs-5 mb-0">${book.author} • ${book.year}</p>
                <p class="card-text text-truncate fs-5">${book.description || ''}</p>

                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" onclick="showBookModal('${book._id}')">
                        <i class="fas fa-eye"></i> View
                    </button>
                    <a href="/books/edit/${book._id}" class="btn btn-outline-secondary">
                        <i class="fas fa-pencil-alt"></i> Edit
                    </a>
                    <button onclick="deleteBook('${book._id}')" class="btn btn-outline-danger">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
`;

const fetchBooks = async () => {
    try {
        const { data } = await axios.get(config.apiBaseUrl, {
            headers: { Authorization: `Bearer ${config.token}` }
        });
        allBooks = data;
        document.getElementById('bookList').innerHTML = allBooks.map(book => renderBookCard(book)).join('');
    } catch (error) {
        console.error("Error loading books:", error);
        alert("Failed to load books");
    }
};

const deleteBook = async (id) => {
    if (confirm("Are you sure you want to delete this book?")) {
        try {
            await axios.delete(`${config.apiBaseUrl}/${id}`, {
                headers: { Authorization: `Bearer ${config.token}` }
            });
            fetchBooks();
        } catch (error) {
            alert("Failed to delete the book.");
        }
    }
};

const showBookModal = (bookId) => {
    const book = allBooks.find(b => b._id === bookId);
    if (!book) return;

    document.getElementById('modalTitle').textContent = book.title;
    document.getElementById('modalImage').src = book.image;
    document.getElementById('modalAuthor').textContent = book.author;
    document.getElementById('modalYear').textContent = book.year;
    document.getElementById('modalDescription').textContent = book.description || "No description available";

    const modal = new bootstrap.Modal(document.getElementById('bookModal'));
    modal.show();
};

document.addEventListener('DOMContentLoaded', fetchBooks);

const handleSearch = () => {
    const query = document.getElementById('searchInput').value.toLowerCase().trim();
    const filteredBooks = allBooks.filter(book => 
        book.title.toLowerCase().includes(query) ||
        book.author.toLowerCase().includes(query)
    );
    document.getElementById('bookList').innerHTML = filteredBooks.map(book => renderBookCard(book)).join('');
};

</script>
@endsection
</body>
</html>
