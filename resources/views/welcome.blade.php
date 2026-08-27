<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Postinger</title>
    @vite(['resources/css/welcome.css', 'resources/js/welcome.js'])

</head>


<body class="welcome-body">
    <header class="flex relative">
        <nav
            class="fixed top-7 left-1/2 transform -translate-x-1/2 w-[90%] bg-gradient-to-r from-[#142f39] via-[#0b3e44] to-[#04373c] backdrop-blur-xl border-t-[1.5px] border-b-[1.5px] border-[#17e9de]/20 rounded-2xl shadow-md z-50">
            <div class="max-w-6xl mx-auto px-6 py-4">
                <div class="flex justify-between items-center">
                    <!-- Logo -->
                    <div class="flex items-center space-x-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Postinger Logo" class="w-10 h-10 object-contain">
                        <span class="text-white text-lg font-semibold">Postinger</span>
                    </div>

                    <!-- Nav Links (Desktop) -->
                    <div class="hidden md:flex justify-center space-x-10">
                        <a href="{{ route('posts.guest') }}"
                            class="relative text-white hover:text-[#15cce4ff] font-medium tracking-wide transition-colors duration-200 after:content-[''] after:absolute after:w-0 after:h-[2px] after:bg-[#15cce4ff] after:left-0 after:-bottom-1 hover:after:w-full after:transition-all after:duration-300">
                            Posts
                        </a>


                        <a href="#"
                            class="relative text-white hover:text-[#15cce4ff] font-medium tracking-wide transition-colors duration-200 after:content-[''] after:absolute after:w-0 after:h-[2px] after:bg-[#15cce4ff] after:left-0 after:-bottom-1 hover:after:w-full after:transition-all after:duration-300">
                            Admin
                        </a>
                        <a href="#"
                            class="relative text-white hover:text-[#15cce4ff] font-medium tracking-wide transition-colors duration-200 after:content-[''] after:absolute after:w-0 after:h-[2px] after:bg-[#15cce4ff] after:left-0 after:-bottom-1 hover:after:w-full after:transition-all after:duration-300">
                            About
                        </a>
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="md:hidden">
                        <button id="menu-btn"
                            class="text-white focus:outline-none transition-transform duration-300 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2.5" stroke="currentColor" class="h-7 w-7" id="menu-icon">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Sliding Mobile Menu -->
        <div id="menu"
            class="fixed top-0 right-0 h-full w-2/3 max-w-xs bg-white/90 backdrop-blur-lg shadow-xl transform translate-x-full transition-transform duration-300 ease-in-out z-50 md:hidden flex flex-col items-start pt-20 pl-6 space-y-6 rounded-l-2xl">

            <!-- Close button -->
            <button id="close-menu" class="absolute top-4 right-4 text-[#142f39] focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Menu links -->
            <a href="{{ route('posts.guest') }}"
                class="text-[#142f39] font-medium hover:bg-[#142f39]/10 px-4 py-2 rounded-md w-full transition-colors">
                Posts
            </a>


            <a href="#"
                class="text-[#142f39] font-medium hover:bg-[#142f39]/10 px-4 py-2 rounded-md w-full transition-colors">Admin</a>
            <a href="#"
                class="text-[#142f39] font-medium hover:bg-[#142f39]/10 px-4 py-2 rounded-md w-full transition-colors">About</a>
        </div>
    </header>


    <div class="container-1">
        <div class="heading-wrapper">
            <h1 class="typing">Welcome to Postinger!</h1>
        </div>
        <div class="auth-cards">
            <a href="{{ route('login') }}" class="tilt-card" data-tilt data-tilt-max="20" data-tilt-speed="400"
                data-tilt-perspective="1000" data-tilt-glare data-tilt-max-glare="0.5">
                <div class="badge-meta">
                    <p class="chapter-index">Login</p>
                </div>
            </a>
            <a href="{{ route('register') }}" class="tilt-card2" data-tilt data-tilt-max="20" data-tilt-speed="400"
                data-tilt-perspective="1000" data-tilt-glare data-tilt-max-glare="0.5">
                <div class="badge-meta2">
                    <p class="chapter-index2">Register</p>
                </div>
            </a>
        </div>
    </div>

     <script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/service-worker.js')
                .then((reg) => console.log('Service worker registered:', reg.scope))
                .catch((err) => console.log('Service worker registration failed:', err));
        });
    }
</body>

</html>
