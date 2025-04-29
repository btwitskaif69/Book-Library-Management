<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Library</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-gray-100">
    <nav class="p-4 bg-blue-500 text-white">
        <div class="container mx-auto flex justify-between">
            <h1 class="text-xl font-bold">📚 Book Library</h1>
            <a href="/logout" class="text-white">Logout</a>
        </div>
    </nav>
    <div class="container mx-auto mt-4">
        @yield('content')
    </div>
</body>
</html>
