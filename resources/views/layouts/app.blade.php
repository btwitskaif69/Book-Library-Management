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
            margin-top: auto;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        }
        .nav-link {
            transition: all 0.3s ease;
            font-weight: 500;
        }
        .nav-link:hover {
            transform: translateY(-2px);
            color: #fff !important;
        }
        .brand-gradient {
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-weight: 700;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navbar -->
    @if (!request()->is('login') && !request()->is('register'))
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand brand-gradient" href="/">📚 Book Library</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item mx-2">
                            <a class="nav-link fw-semibold" href="/">Home</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="nav-link fw-semibold" href="/books/create">
                                <i class="fas fa-plus-circle me-1"></i>Add Book
                            </a>
                        </li>
                        <li class="nav-item mx-2">
                            <form action="{{ route('logout') }}" method="POST">
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
    <main class="main-content {{ request()->is('login') || request()->is('register') ? '' : 'my-5 py-4' }}" style="background-color: black;">
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
                            © {{ date('Y') }} Book Library. All Rights Reserved.
                        </p>
                        <p class="small text-muted mt-1">
                            Built with <i class="fas fa-heart text-danger"></i> by Your Team
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    @endif
</body>
</html>