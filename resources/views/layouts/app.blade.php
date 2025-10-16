

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ 'Postinger' }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Livewire Styles -->
    @livewireStyles

<body class="font-sans antialiased min-h-screen bg-cover bg-center bg-no-repeat bg-fixed"
    style="background-image: url('{{ asset('images/social-media.JPG') }}')">
    <div class="min-h-screen bg-white/75">
        @include('layouts.navigation')

        <!-- Page Heading -->
           <header class="bg-gradient-to-r from-[#0f2027] via-[#203a43] to-[#2c5364] 
               backdrop-blur-lg border-b border-white/10 shadow-lg sticky top-0 z-40">
                <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
                    @isset($header)
            <h2 class="text-xl md:text-2xl font-medium tracking-wide text-white/95">
                {{ $header }}
            </h2>
             </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="container">
            {{ $slot }}
        </main>
    </div>
    <!-- Livewire Scripts -->
    @livewireScripts
</body>

<footer class="footer bg-white/75">
    <div>
        <p>&copy; {{ date('Y') }} Postinger. All rights reserved.</p>
    </div>
</footer>


