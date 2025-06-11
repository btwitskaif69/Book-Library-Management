<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Library</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .main-content {
            flex: 1;
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .footer {
            background-color: #000;
            color: #fff;
        }
        .nav-link {
            transition: all 0.3s ease;
            font-weight: 500;
        }
        .nav-link:hover {
            transform: translateY(-2px);
            color: #fff !important;
        }
        
    </style>
</head>
<body style="background-color: black;">
    <!-- Navbar -->
    @if (!request()->is('login') && !request()->is('register'))
    <nav class="navbar navbar-expand-lg sticky-top" style="background-color: #000; box-shadow: 0 4px 12px rgba(255,255,255,0.05);">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="/" style="font-size: 1.5rem;">
                <i class="bi bi-book me-2"></i>LibraryHub
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav gap-4">
                    <li class="nav-item">
                        <a class="nav-link text-white fw-semibold" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white fw-semibold" href="/books/create">
                            <i class="fas fa-plus-circle me-1"></i>Add Book
                        </a>
                    </li>
                    <li class="nav-item d-flex align-items-center">
                        <form action="{{ route('logout') }}" method="POST" class="mb-0">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link text-white fw-semibold p-0">
                                <i class="fas fa-sign-out-alt me-1"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
@endif



    <!-- Main Content -->
    <main class="main-content {{ request()->is('login') || request()->is('register') ? '' : 'my-0 py-0' }}" style="background-color: black;">
    <div class="container" style="background-color: black;">
        @yield('content')
    </div>
</main>


    <!-- Footer -->
    @if (!request()->is('login') && !request()->is('register'))
        <footer class="footer py-4 border-top">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        <ul class="nav justify-content-center justify-content-md-start">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="/">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="/books/create">Add Book</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <p class="mb-0 text-white">
                            © {{ date('Y') }} LibraryHub. All Rights Reserved.
                        </p>
                        <p class="small mt-1">
                            Built with <i class="fas fa-heart text-danger"></i> by btwitskaif
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    @endif
</body>
</html>