<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Admin | Rosa Spa')</title>
    
    <!-- Favicons -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Noto+Sans:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Admin Styles -->
    <link href="{{ asset('admin/css/admin-style.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/time-filter.css') }}?v={{ time() }}" rel="stylesheet">
    
    <!-- Additional styles from child pages -->
    @yield('styles')
    
    <style>
        :root {
            --sidebar-width: 250px;
            --header-height: 60px;
            --footer-height: 50px;
        }
    </style>
</head>
<body>
    <div id="app" class="admin-container">
        <!-- Sidebar -->
        @include('backend.partials.sidebar')
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            @include('backend.partials.header')
            
            <!-- Content Area -->
            <div class="content-wrapper">
                <div class="container-fluid py-3">
                    @yield('content')
                </div>
            </div>
            
            <!-- Footer -->
            @include('backend.partials.footer')
        </div>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Admin Scripts -->
    <script src="{{ asset('admin/js/admin-script.js') }}"></script>
    <script src="{{ asset('admin/js/time-filter.js') }}?v={{ time() }}"></script>
    
    <!-- Additional scripts from child pages -->
    @yield('scripts')
</body>
</html>