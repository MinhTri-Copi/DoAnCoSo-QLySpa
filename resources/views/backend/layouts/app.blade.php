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
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
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
            --primary-color: #ff6b8b;
            --primary-light: #ffd0d9;
            --primary-dark: #e84e6f;
            --primary-pink: #ff6b95;
            --light-pink: #ffdbe9;
            --dark-pink: #e84a78;
        }
        
        /* Override sidebar colors for consistent pink theme */
        .sidebar {
            --primary-color: #ff6b95;
            --primary-light: #ffdbe9;
            --primary-dark: #e84a78;
        }
        
        .menu-link:hover {
            background-color: #ff6b95 !important;
        }
        
        .submenu li a:hover {
            background-color: #ff6b95 !important;
        }
        
        .menu-item.active .menu-link, 
        .menu-link.active {
            color: white !important;
            background-color: #ff6b95 !important;
        }
        
        /* Welcome banner style */
        .welcome-banner {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            background: linear-gradient(145deg, #f58cba, #db7093);
            animation: softPulse 4s infinite alternate, floatAnimation 6s ease-in-out infinite;
            transition: all 0.5s ease;
            box-shadow: 0 5px 15px rgba(219, 112, 147, 0.3);
            transform-origin: center center;
            width: 100%;
            padding: 30px 35px;
            margin-bottom: 30px;
            color: white;
        }

        .welcome-banner:before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            z-index: -1;
            background: linear-gradient(45deg, 
                #ff7eb3, #ff758c, #ff7eb3, #ff8e8c, 
                #fdae9e, #ff7eb3, #ff758c, #ff7eb3);
            background-size: 400%;
            border-radius: 14px;
            animation: borderGlow 12s linear infinite;
            filter: blur(10px);
            opacity: 0.7;
        }

        .welcome-banner:after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 100%;
            background: linear-gradient(90deg, 
                rgba(255,255,255,0) 0%, 
                rgba(255,255,255,0.1) 25%, 
                rgba(255,255,255,0.2) 50%, 
                rgba(255,255,255,0.1) 75%, 
                rgba(255,255,255,0) 100%);
            background-size: 200% 100%;
            animation: shimmer 6s infinite;
            z-index: 1;
            pointer-events: none;
        }

        .welcome-banner h1, .welcome-banner p {
            position: relative;
            z-index: 2;
        }

        .welcome-banner h1 {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 12px;
            color: white;
        }

        .welcome-banner p {
            font-size: 1.05rem;
            opacity: 0.9;
            margin-bottom: 5px;
            position: relative;
            z-index: 1;
            max-width: 80%;
        }

        .shine-line {
            position: absolute;
            top: 0;
            left: 0;
            width: 6px;
            height: 6px;
            background: white;
            border-radius: 50%;
            box-shadow: 0 0 20px 5px rgba(255, 255, 255, 0.95);
            z-index: 2;
            animation: corner-to-corner 12s infinite cubic-bezier(0.25, 0.1, 0.25, 1);
            opacity: 0;
        }

        .header-actions {
            position: relative;
            z-index: 2;
        }

        .spa-btn {
            background: rgba(255, 255, 255, 0.9);
            color: #db7093;
            border: none;
            font-size: 1rem;
            font-weight: 600;
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .spa-btn i {
            font-size: 0.8rem;
            background: rgba(219, 112, 147, 0.15);
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s ease;
        }

        .spa-btn:hover {
            background: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            color: #e84a78;
            text-decoration: none;
        }

        .spa-btn:hover i {
            background: rgba(219, 112, 147, 0.25);
            transform: rotate(90deg);
        }

        @keyframes softPulse {
            0% {
                box-shadow: 0 5px 15px rgba(219, 112, 147, 0.3);
            }
            100% {
                box-shadow: 0 8px 25px rgba(219, 112, 147, 0.5);
            }
        }

        @keyframes floatAnimation {
            0% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-5px);
            }
            100% {
                transform: translateY(0);
            }
        }

        @keyframes borderGlow {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }
            100% {
                background-position: 200% 0;
            }
        }

        @keyframes corner-to-corner {
            0% {
                opacity: 0;
                top: 2px;
                left: 2px;
                box-shadow: 0 0 10px 2px rgba(255, 255, 255, 0.7);
            }
            5% {
                opacity: 1;
                top: 2px;
                left: 2px;
                box-shadow: 0 0 15px 3px rgba(255, 255, 255, 0.8);
            }
            
            30% {
                top: 40%;
                left: 2px;
                box-shadow: 0 0 20px 5px rgba(255, 255, 255, 0.9);
                opacity: 1;
            }
            
            60% {
                top: calc(100% - 2px);
                left: 60%;
                box-shadow: 0 0 25px 6px rgba(255, 255, 255, 1);
                opacity: 1;
            }
            
            80% {
                top: calc(100% - 2px);
                left: calc(100% - 2px);
                box-shadow: 0 0 20px 5px rgba(255, 255, 255, 0.9);
                opacity: 1;
            }
            
            85% {
                opacity: 0.7;
            }
            
            90% {
                opacity: 0;
            }
            
            100% {
                opacity: 0;
                top: calc(100% - 2px);
                left: calc(100% - 2px);
            }
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