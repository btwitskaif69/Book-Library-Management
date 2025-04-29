<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="flex flex-col sm:flex-row justify-between items-center p-6 border-b border-gray-200">
            <h2 class="text-3xl font-bold text-gray-800 mb-4 sm:mb-0">Book Management</h2>
            <a href="/books/create" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg transition-all duration-300 flex items-center gap-2 shadow-sm hover:shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add New Book
            </a>
        </div>

        <div class="overflow-x-auto rounded-b-xl">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Author</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Year</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="bookList" class="bg-white divide-y divide-gray-200">
                    <!-- Books will be inserted here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
axios.get("http://localhost:3000/api/books", {
    headers: { Authorization: "Bearer " + localStorage.getItem("token") }
}).then(res => {
    let booksHtml = "";
    res.data.forEach(book => {
        booksHtml += `<tr class="hover:bg-gray-50 transition-colors duration-200">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${book.title}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${book.author}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${book.year}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm">
                <div class="flex items-center gap-3">
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
            </td>
        </tr>`;
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