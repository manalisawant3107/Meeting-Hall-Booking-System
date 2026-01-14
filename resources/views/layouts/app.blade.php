<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Meeting Hall Booking</title>
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/3004/3004518.png" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-color: #0ea5e9; /* Sky Blue */
            --secondary-color: #0284c7;
            --accent-color: #e0f2fe;
            --dark-bg: #f8fafc; /* Very light gray */
            --card-bg: #ffffff;
            --text-main: #0f172a; /* Dark Slate */
            --text-muted: #64748b;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-main);
        }

        .navbar {
            background-color: var(--card-bg) !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border-bottom: 1px solid rgba(0,0,0,0.05); /* Added border for separation */
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--text-main) !important;
            font-size: 1.5rem;
        }

        .nav-link {
            color: var(--text-muted) !important;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary-color) !important;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(14, 165, 233, 0.2);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
        }

        .card {
            background-color: var(--card-bg);
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .form-control, .form-select {
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            color: var(--text-main);
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
        }

        .form-control:focus, .form-select:focus {
            background-color: #ffffff;
            border-color: var(--primary-color);
            color: var(--text-main);
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15);
        }
        
        .form-control::placeholder {
            color: #94a3b8;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 0.5rem;
        }

        footer {
            background-color: var(--card-bg);
            padding: 2rem 0;
            margin-top: 4rem;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            color: var(--text-muted);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1; 
            border-radius: 5px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8; 
        }
    </style>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--single {
            height: 38px !important;
            padding: 5px;
        }
        /* Match Bootstrap 5 form-control style */
        .select2-container--default .select2-selection--multiple {
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 0.5rem; /* Match Bootstrap 5 rounded similar to form-control */
            min-height: 48px; /* Match approximately the height of other inputs with labels or ensure consistency */
            padding: 4px 8px; /* Adjustment for internal spacing */
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #0ea5e9; /* Primary color focus */
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15);
        }

        @media (max-width: 768px) {
            .form-control, .form-select, .form-label, .btn {
                font-size: 14px !important; /* Force smaller text */
            }
            
            .form-control, .form-select {
                padding: 0.4rem 0.6rem !important; /* Compact padding */
                min-height: 38px; /* Standard height */
            }

            /* Fix Date Range Overflow - Stack inputs on mobile */
            .input-group {
                flex-direction: column;
            }
            
            .input-group > .form-control, 
            .input-group > .input-group-text {
                width: 100% !important;
                border-radius: 0.375rem !important;
                margin-bottom: 5px;
            }

            /* Hide the 'to' separator on mobile if stacked, or style it */
            .input-group-text {
                display: none; 
            }

            .select2-container--default .select2-selection--multiple {
                min-height: 36px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="bi bi-building-fill-check me-2 text-primary"></i>SpaceBook
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Find a Space</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">My Bookings</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>

    <footer class="text-center">
        <div class="container">
            <p class="mb-0 text-muted">&copy; {{ date('Y') }} SpaceBook Project. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @stack('scripts')
</body>
</html>
