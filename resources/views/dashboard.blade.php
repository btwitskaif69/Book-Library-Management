<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliotheca | Book Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-primary: 99 102 241;
            --color-secondary: 168 85 247;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            background: radial-gradient(circle at 10% 20%, rgba(248, 250, 252, 0.9) 0%, rgba(241, 245, 249, 0.9) 90%);
        }
        
        .title-font {
            font-family: 'Playfair Display', serif;
        }
        
        .book-card {
            perspective: 1000px;
            transform-style: preserve-3d;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .book-card:hover {
            transform: translateY(-5px) scale(1.02);
        }
        
        .book-cover {
            transform-style: preserve-3d;
            transform: rotateY(-5deg);
            box-shadow: 10px 10px 30px -10px rgba(0,0,0,0.2);
            transition: all 0.4s ease;
        }
        
        .book-card:hover .book-cover {
            transform: rotateY(-10deg) rotateX(5deg) translateY(-5px);
            box-shadow: 15px 15px 40px -5px rgba(0,0,0,0.3);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, rgb(var(--color-primary)), rgb(var(--color-secondary)));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .floating {
            animation: float 6s ease-in-out infinite;
        }
        
        .stagger-delay {
            animation-delay: calc(var(--order) * 100ms);
        }
    </style>
</head>
<body class="min-h-screen">
@extends('layouts.app')

@section('content')
<div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header with search and stats -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-12 gap-6">
        <div>
            <h1 class="title-font text-5xl font-bold text-gray-900 mb-2">Bibliotheca</h1>
            <p class="text-lg text-gray-600 max-w-2xl">Your personal literary universe. Manage, explore, and discover.</p>
        </div>
        
        <div class="w-full lg:w-auto flex flex-col sm:flex-row gap-4">
            <div class="relative flex-grow">
                <input type="text" placeholder="Search books..." 
                    class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
            </div>
            <a href="/books/create" class="whitespace-nowrap bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white px-6 py-3 rounded-lg transition-all duration-300 flex items-center gap-2 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <i class="fas fa-plus"></i>
                Add Book
            </a>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-full bg-indigo-50 text-indigo-600">
                    <i class="fas fa-book text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500">Total Books</p>
                    <h3 class="text-2xl font-bold" id="totalBooks">0</h3>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-full bg-green-50 text-green-600">
                    <i class="fas fa-user-edit text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500">Authors</p>
                    <h3 class="text-2xl font-bold" id="totalAuthors">0</h3>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-full bg-blue-50 text-blue-600">
                    <i class="fas fa-calendar-alt text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500">Oldest Book</p>
                    <h3 class="text-2xl font-bold" id="oldestBook">-</h3>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-full bg-purple-50 text-purple-600">
                    <i class="fas fa-star text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500">New Additions</p>
                    <h3 class="text-2xl font-bold" id="recentBooks">0</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Book Grid -->
        <div class="flex-1">
            <div class="flex justify-between items-center mb-6">
                <h2 class="title-font text-3xl font-semibold text-gray-800">Your Collection</h2>
                <div class="flex gap-2">
                    <button class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-th-large text-indigo-600"></i>
                    </button>
                    <button class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-list text-gray-400"></i>
                    </button>
                </div>
            </div>
            
            <div id="bookList" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <!-- Books will be loaded here -->
            </div>
        </div>
        
        <!-- Sidebar with recently added -->
        <div class="lg:w-80 xl:w-96 space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="title-font text-xl font-semibold mb-4">Recently Added</h3>
                <div id="recentlyAdded" class="space-y-4">
                    <!-- Recently added books will be loaded here -->
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 p-6 rounded-xl shadow-lg text-white">
                <h3 class="title-font text-xl font-semibold mb-3">Reading Challenge</h3>
                <p class="mb-4">You've completed 8 of 12 books this year</p>
                <div class="w-full bg-white bg-opacity-20 rounded-full h-2.5 mb-4">
                    <div class="bg-white h-2.5 rounded-full" style="width: 66%"></div>
                </div>
                <button class="w-full bg-white text-indigo-600 py-2 rounded-lg font-medium hover:bg-opacity-90 transition-all">
                    View Challenge
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Book Detail Modal (hidden by default) -->
<div id="bookModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal content will be loaded here -->
    </div>
</div>

<script>
// Configuration
const config = {
    apiBaseUrl: "http://localhost:3000/api/books",
    token: localStorage.getItem("token")
};

// DOM Elements
const elements = {
    bookList: document.getElementById('bookList'),
    recentlyAdded: document.getElementById('recentlyAdded'),
    totalBooks: document.getElementById('totalBooks'),
    totalAuthors: document.getElementById('totalAuthors'),
    oldestBook: document.getElementById('oldestBook'),
    recentBooks: document.getElementById('recentBooks'),
    bookModal: document.getElementById('bookModal')
};

// State
let allBooks = [];

// Helper Functions
const getRandomColor = () => {
    const colors = [
        'bg-gradient-to-br from-blue-100 to-blue-50',
        'bg-gradient-to-br from-purple-100 to-purple-50',
        'bg-gradient-to-br from-pink-100 to-pink-50',
        'bg-gradient-to-br from-green-100 to-green-50',
        'bg-gradient-to-br from-yellow-100 to-yellow-50',
        'bg-gradient-to-br from-red-100 to-red-50'
    ];
    return colors[Math.floor(Math.random() * colors.length)];
};

const getInitials = (title) => {
    return title.split(' ').map(word => word[0]).join('').substring(0, 3).toUpperCase();
};

const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
};

const calculateStats = (books) => {
    // Total books
    elements.totalBooks.textContent = books.length;
    
    // Unique authors
    const authors = [...new Set(books.map(book => book.author))];
    elements.totalAuthors.textContent = authors.length;
    
    // Oldest book
    if (books.length > 0) {
        const oldest = books.reduce((prev, current) => 
            (prev.year < current.year) ? prev : current
        );
        elements.oldestBook.textContent = oldest.year;
    }
    
    // Recent books (last 30 days)
    const thirtyDaysAgo = new Date();
    thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
    const recent = books.filter(book => {
        const addedDate = new Date(book.createdAt || new Date());
        return addedDate > thirtyDaysAgo;
    });
    elements.recentBooks.textContent = recent.length;
};

const renderBookCard = (book, order) => {
    const colorClass = getRandomColor();
    const initials = getInitials(book.title);
    
    return `
    <div class="book-card" style="--order: ${order}">
        <div class="bg-white rounded-xl shadow-md overflow-hidden h-full flex flex-col border border-gray-100 hover:border-indigo-100 transition-all">
            <div class="p-5 flex justify-center items-center bg-gray-50">
                <div class="book-cover ${colorClass} w-40 h-48 rounded-lg shadow-lg flex items-center justify-center relative overflow-hidden">
                    <span class="text-4xl font-bold text-gray-700 opacity-30 z-10">${initials}</span>
                    <div class="absolute inset-0 bg-white bg-opacity-20"></div>
                </div>
            </div>
            
            <div class="p-5 pt-0 flex-grow">
                <div class="flex justify-between items-start mt-4 gap-2">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-1 line-clamp-2">${book.title}</h3>
                        <p class="text-gray-600 mb-2 italic">${book.author}</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        ${book.year}
                    </span>
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                    <button onclick="viewBookDetails('${book._id}')" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1 transition-colors">
                        <i class="fas fa-eye"></i> Quick View
                    </button>
                    <div class="flex items-center gap-1">
                        <button onclick="event.stopPropagation(); editBook('${book._id}')" class="text-gray-500 hover:text-indigo-600 p-1.5 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                            <i class="fas fa-pencil-alt text-sm"></i>
                        </button>
                        <button onclick="event.stopPropagation(); deleteBook('${book._id}')" class="text-gray-500 hover:text-red-600 p-1.5 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                            <i class="fas fa-trash-alt text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>`;
};

const renderRecentlyAdded = (books) => {
    const recentBooks = [...books]
        .sort((a, b) => new Date(b.createdAt || new Date()) - new Date(a.createdAt || new Date()))
        .slice(0, 3);
    
    return recentBooks.map(book => `
        <div class="flex gap-3 items-start p-2 hover:bg-gray-50 rounded-lg transition-colors cursor-pointer" onclick="viewBookDetails('${book._id}')">
            <div class="w-12 h-16 bg-indigo-50 rounded-md flex items-center justify-center text-indigo-600 flex-shrink-0">
                <i class="fas fa-book"></i>
            </div>
            <div class="flex-grow">
                <h4 class="font-medium text-gray-800 line-clamp-1">${book.title}</h4>
                <p class="text-sm text-gray-500">${book.author}</p>
            </div>
        </div>
    `).join('');
};

// API Functions
const fetchBooks = async () => {
    try {
        const response = await axios.get(config.apiBaseUrl, {
            headers: { Authorization: "Bearer " + config.token }
        });
        
        allBooks = response.data;
        calculateStats(allBooks);
        
        // Render main book grid
        elements.bookList.innerHTML = allBooks
            .map((book, index) => renderBookCard(book, index))
            .join('');
        
        // Render recently added sidebar
        elements.recentlyAdded.innerHTML = renderRecentlyAdded(allBooks);
        
    } catch (error) {
        if (error.response && error.response.status === 401) {
            window.location.href = "/login";
        } else {
            console.error("Error fetching books:", error);
            alert("Failed to load books. Please try again.");
        }
    }
};

// Action Functions
const viewBookDetails = (bookId) => {
    const book = allBooks.find(b => b._id === bookId);
    if (!book) return;
    
    elements.bookModal.innerHTML = `
        <div class="p-6">
            <div class="flex justify-between items-start mb-4">
                <h3 class="title-font text-2xl font-bold text-gray-800">${book.title}</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="flex flex-col md:flex-row gap-6">
                <div class="w-full md:w-1/3 flex justify-center">
                    <div class="book-cover bg-gradient-to-br from-indigo-100 to-purple-100 w-full h-64 rounded-xl shadow-lg flex items-center justify-center">
                        <span class="text-6xl font-bold text-gray-700 opacity-30">${getInitials(book.title)}</span>
                    </div>
                </div>
                
                <div class="w-full md:w-2/3">
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Author</h4>
                            <p class="text-lg">${book.author}</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Publication Year</h4>
                            <p class="text-lg">${book.year}</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Added On</h4>
                            <p class="text-lg">${book.createdAt ? formatDate(book.createdAt) : 'Unknown'}</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-6 border-t border-gray-200 flex gap-3">
                        <button onclick="editBook('${book._id}')" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-lg transition-colors">
                            Edit Details
                        </button>
                        <button onclick="deleteBook('${book._id}')" class="flex-1 bg-white border border-gray-300 hover:bg-gray-50 text-gray-800 py-2 px-4 rounded-lg transition-colors">
                            Remove Book
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    elements.bookModal.classList.remove('hidden');
};

const closeModal = () => {
    elements.bookModal.classList.add('hidden');
};

const editBook = (bookId) => {
    window.location.href = `/books/edit/${bookId}`;
};

const deleteBook = async (bookId) => {
    if (confirm("Are you sure you want to delete this book? This action cannot be undone.")) {
        try {
            await axios.delete(`${config.apiBaseUrl}/${bookId}`, {
                headers: { Authorization: "Bearer " + config.token }
            });
            
            // Close modal if open
            closeModal();
            
            // Refresh the book list
            fetchBooks();
            
        } catch (error) {
            console.error("Error deleting book:", error);
            alert("Failed to delete book. Please try again.");
        }
    }
};

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    fetchBooks();
    
    // Close modal when clicking outside
    elements.bookModal.addEventListener('click', (e) => {
        if (e.target === elements.bookModal) {
            closeModal();
        }
    });
});
</script>
@endsection
</body>
</html>