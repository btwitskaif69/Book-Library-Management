<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body>
@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white p-6 rounded shadow-md">
    <h2 class="text-2xl font-bold mb-4">Edit Book</h2>
    <form id="editBookForm">
        <input type="text" id="title" class="border p-2 w-full mb-2">
        <input type="text" id="author" class="border p-2 w-full mb-2">
        <input type="number" id="year" class="border p-2 w-full mb-2">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 w-full">Update Book</button>
    </form>
</div>

<script>
const bookId = window.location.pathname.split("/").pop();

axios.get(`http://localhost:3000/api/books/${bookId}`, {
    headers: { Authorization: "Bearer " + localStorage.getItem("token") }
}).then(res => {
    const book = res.data;
    document.getElementById("title").value = book.title;
    document.getElementById("author").value = book.author;
    document.getElementById("year").value = book.year;
});

document.getElementById("editBookForm").addEventListener("submit", function(e) {
    e.preventDefault();
    axios.put(`http://localhost:3000/api/books/${bookId}`, {
        title: document.getElementById("title").value,
        author: document.getElementById("author").value,
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

</body>
</html>