<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Postinger</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/guest.js'])
    @livewireStyles
</head>

<body class="font-sans antialiased min-h-screen bg-fixed overflow-x-hidden" style="background-image: url('{{ asset('images/social-media.JPG') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="min-h-screen bg-white/75 relative w-full">

      <header class="bg-white/25 backdrop-blur-md border-b border-[#0a2d2d]/70 shadow-md sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('register') }}">
                        <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center hover:bg-gray-400 transition duration-200">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </a>
                    <h1 class="text-lg font-semibold text-black/90 tracking-wide">Guest</h1>
                </div>
                <div class="flex items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Postinger Logo" class="h-10">
                </div>
                <div class="flex items-center gap-3 md:hidden">
                    <button id="guest-menu-toggle" class="text-[#0a2d2d] hover:text-[#0a2d2d]/80 focus:outline-none" aria-label="Toggle menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                    </button>
                </div>
                <div class="hidden md:flex items-center gap-3">
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 text-sm font-medium text-white bg-[#0a2d2d] hover:bg-transparent hover:text-[#0a2d2d] hover:border hover:border-[#0a2d2d] rounded-md transition duration-200 ease-in-out">
                       Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="px-4 py-2 text-sm font-medium text-[#0a2d2d] bg-transparent border border-[#0a2d2d] hover:bg-[#0a2d2d] hover:text-white rounded-md transition duration-200 ease-in-out">
                       Register
                    </a>
                </div>
                <div id="guest-mobile-menu" class="fixed inset-y-0 right-0 z-50 w-64 bg-white shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out md:hidden" style="max-width: 100vw;">
                    <div class="flex flex-col h-full">
                        <div class="flex justify-end p-4 bg-white">
                            <button id="guest-menu-close" class="text-[#0a2d2d] hover:text-[#0a2d2d]/80 focus:outline-none" aria-label="Close menu">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="flex-1 p-4 bg-white">
                            <div class="flex flex-col gap-4">
                                <a href="{{ route('login') }}"
                                   class="px-4 py-2 text-sm font-medium text-white bg-[#0a2d2d] hover:bg-[#0a2d2d]/20 hover:text-[#0a2d2d] rounded-md transition duration-200 ease-in-out">
                                   Login
                                </a>
                                <a href="{{ route('register') }}"
                                   class="px-4 py-2 text-sm font-medium text-[#0a2d2d] bg-transparent border border-[#0a2d2d] hover:bg-[#0a2d2d]/20 rounded-md transition duration-200 ease-in-out">
                                   Register
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <main class="container-index max-w-7xl mx-auto px-6 py-4">
            @yield('content')
        </main>

        <footer class="footer bg-white/75">
            <div>
                <p>&copy; {{ date('Y') }} Postinger. All rights reserved.</p>
            </div>
        </footer>
    </div>

    @livewireScripts
</body>

</html>
