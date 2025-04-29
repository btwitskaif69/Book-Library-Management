@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow-md">
    <h2 class="text-2xl font-bold mb-4">Book List</h2>
    <table class="w-full border">
        <thead>
            <tr>
                <th class="border px-4 py-2">Title</th>
                <th class="border px-4 py-2">Author</th>
                <th class="border px-4 py-2">Year</th>
            </tr>
        </thead>
        <tbody id="bookList"></tbody>
    </table>
</div>

<script>
axios.get("http://localhost:3000/api/books", {
    headers: { Authorization: "Bearer " + localStorage.getItem("token") }
}).then(res => {
    let booksHtml = "";
    res.data.forEach(book => {
        booksHtml += `<tr>
            <td class="border px-4 py-2">${book.title}</td>
            <td class="border px-4 py-2">${book.author}</td>
            <td class="border px-4 py-2">${book.year}</td>
        </tr>`;
    });
    document.getElementById("bookList").innerHTML = booksHtml;
}).catch(() => {
    window.location.href = "/login";
});
</script>
@endsection
