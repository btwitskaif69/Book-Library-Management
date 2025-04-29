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
    <h2 class="text-2xl font-bold mb-4">Add New Book</h2>
    <form id="createBookForm">
        <input type="text" id="title" placeholder="Title" class="border p-2 w-full mb-2">
        <input type="text" id="author" placeholder="Author" class="border p-2 w-full mb-2">
        <input type="number" id="year" placeholder="Year" class="border p-2 w-full mb-2">
        <button type="submit" class="bg-green-500 text-white px-4 py-2 w-full">Add Book</button>
    </form>
</div>

<script>
document.getElementById("createBookForm").addEventListener("submit", function(e) {
    e.preventDefault();
    axios.post("http://localhost:3000/api/books", {
        title: document.getElementById("title").value,
        author: document.getElementById("author").value,
        year: document.getElementById("year").value
    }, {
        headers: { Authorization: "Bearer " + localStorage.getItem("token") }
    }).then(() => {
        window.location.href = "/dashboard";
    }).catch(err => {
        alert("Error adding book");
    });
});
</script>
@endsection

</body>
</html>