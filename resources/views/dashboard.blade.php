<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .book-card {
            animation: fadeIn 0.4s ease-out forwards;
            opacity: 0;
        }
        .book-card:hover .book-cover {
            transform: rotate(5deg) scale(1.05);
        }
    </style>
</head>
<body class="bg-gray-50 font-['Inter']">
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-8">
        <div>
            <h2 class="text-4xl font-bold text-gray-800 mb-2">Book Collection</h2>
            <p class="text-gray-500">Manage your library with ease</p>
        </div>
        <a href="/books/create" class="mt-4 sm:mt-0 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white px-6 py-3 rounded-lg transition-all duration-300 flex items-center gap-2 shadow-md hover:shadow-lg transform hover:-translate-y-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add New Book
        </a>
    </div>

    <div id="bookList" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        <!-- Book cards will be inserted here -->
    </div>
</div>

<script>
axios.get("http://localhost:3000/api/books", {
    headers: { Authorization: "Bearer " + localStorage.getItem("token") }
}).then(res => {
    let booksHtml = "";
    res.data.forEach((book, index) => {
        // Generate a random pastel color for the book cover
        const colors = ['bg-blue-100', 'bg-purple-100', 'bg-pink-100', 'bg-green-100', 'bg-yellow-100'];
        const randomColor = colors[Math.floor(Math.random() * colors.length)];
        
        // Extract initials for the cover
        const initials = book.title.split(' ').map(word => word[0]).join('').substring(0, 2);
        
        booksHtml += `
        <div class="book-card" style="animation-delay: ${index * 0.1}s">
            <div class="relative bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 h-full flex flex-col">
                <div class="p-5 flex justify-center items-center">
                    <div class="book-cover ${randomColor} w-32 h-40 rounded-lg shadow-md flex items-center justify-center transition-transform duration-300">
                        <span class="text-3xl font-bold text-gray-700 opacity-70">${initials}</span>
                    </div>
                </div>
                
                <div class="p-5 pt-0 flex-grow">
                    <h3 class="text-xl font-bold text-gray-800 mb-1 truncate">${book.title}</h3>
                    <p class="text-gray-600 mb-3 italic">${book.author}</p>
                    
                    <div class="flex justify-between items-center mt-4">
                        <span class="inline-block bg-gray-100 rounded-full px-3 py-1 text-sm font-semibold text-gray-700">
                            Published: ${book.year}
                        </span>
                        
                        <div class="flex items-center gap-2">
                            <a href="/books/edit/${book._id}" class="text-indigo-600 hover:text-indigo-900 p-2 hover:bg-indigo-50 rounded-lg transition-colors duration-200" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                            </a>
                            <button onclick="deleteBook('${book._id}')" class="text-red-600 hover:text-red-900 p-2 hover:bg-red-50 rounded-lg transition-colors duration-200" title="Delete">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
    });
    document.getElementById("bookList").innerHTML = booksHtml;
}).catch(() => {
    window.location.href = "/login";
});

function deleteBook(id) {
    if (confirm("Are you sure you want to delete this book?")) {
        axios.delete(`http://localhost:3000/api/books/${id}`, {
            headers: { Authorization: "Bearer " + localStorage.getItem("token") }
        }).then(() => {
            location.reload();
        }).catch(() => {
            alert("Failed to delete book");
        });
    }
}
</script>
@endsection
</body>
</html>