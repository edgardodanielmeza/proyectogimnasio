<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased">
    <div class="flex flex-col min-h-screen bg-neutral-100">

        <!-- Navbar -->
        <nav class="bg-neutral-900 text-neutral-100 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Logo and App Name -->
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/workflow-mark-primary.svg" alt="Logo Placeholder"> {{-- Consider using primary color for logo --}}
                        </div>
                        <div class="ml-3">
                            <span class="font-semibold text-xl text-white">{{ config('app.name', 'GimnasioApp') }}</span>
                        </div>
                    </div>

                    <!-- Main Navigation -->
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-4">
                            <a href="{{ route('dashboard') ?? '#' }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-primary text-white' : 'text-neutral-300 hover:bg-neutral-700 hover:text-white' }}">Dashboard</a>
                            <a href="{{ route('membresias') ?? '#' }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('membresias') ? 'bg-primary text-white' : 'text-neutral-300 hover:bg-neutral-700 hover:text-white' }}">Membresías</a>
                            <a href="{{ route('tipos-membresia.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('tipos-membresia.index') ? 'bg-primary text-white' : 'text-neutral-300 hover:bg-neutral-700 hover:text-white' }}">Tipos de Membresía</a>
                            {{-- Add other main navigation links here, following the pattern --}}
                        </div>
                    </div>

                    <!-- User Menu -->
                    <div class="hidden md:block">
                        <div class="ml-4 flex items-center md:ml-6">
                            <button class="p-1 rounded-full text-neutral-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-neutral-800 focus:ring-white">
                                <span class="sr-only">View notifications</span>
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </button>

                            <div class="ml-3 relative">
                                <div>
                                    <button type="button" class="max-w-xs bg-neutral-800 rounded-full flex items-center text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-neutral-800 focus:ring-white" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                        <span class="sr-only">Open user menu</span>
                                        <img class="h-8 w-8 rounded-full" src="https://via.placeholder.com/150/CBD5E1/1E293B/?text=U" alt="User Avatar"> {{-- Neutral avatar --}}
                                    </button>
                                </div>
                                <div class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1" id="user-menu-dropdown">
                                    <a href="#" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100" role="menuitem" tabindex="-1">Tu Perfil</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100" role="menuitem" tabindex="-1">Configuración</a>
                                    <form method="POST" action="{{ route('logout') ?? '#' }}">
                                        @csrf
                                        <a href="{{ route('logout') ?? '#' }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-100" role="menuitem" tabindex="-1">
                                            Cerrar Sesión
                                        </a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="-mr-2 flex md:hidden">
                        <button type="button" class="bg-neutral-800 inline-flex items-center justify-center p-2 rounded-md text-neutral-400 hover:text-white hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false" id="mobile-menu-button">
                            <span class="sr-only">Open main menu</span>
                            <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                            <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="md:hidden hidden" id="mobile-menu">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <a href="{{ route('dashboard') ?? '#' }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('dashboard') ? 'bg-primary text-white' : 'text-neutral-300 hover:bg-neutral-700 hover:text-white' }}">Dashboard</a>
                    <a href="{{ route('membresias') ?? '#' }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('membresias') ? 'bg-primary text-white' : 'text-neutral-300 hover:bg-neutral-700 hover:text-white' }}">Membresías</a>
                    <a href="{{ route('tipos-membresia.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('tipos-membresia.index') ? 'bg-primary text-white' : 'text-neutral-300 hover:bg-neutral-700 hover:text-white' }}">Tipos de Membresía</a>
                </div>
                <div class="pt-4 pb-3 border-t border-neutral-700">
                    <div class="flex items-center px-5">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full" src="https://via.placeholder.com/150/CBD5E1/1E293B/?text=U" alt="User Avatar">
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium leading-none text-white">(Usuario Nombre)</div>
                            <div class="text-sm font-medium leading-none text-neutral-400">(usuario@example.com)</div>
                        </div>
                        <button class="ml-auto bg-neutral-800 flex-shrink-0 p-1 rounded-full text-neutral-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-neutral-800 focus:ring-white">
                            <span class="sr-only">View notifications</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                        </button>
                    </div>
                    <div class="mt-3 px-2 space-y-1">
                        <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-neutral-400 hover:text-white hover:bg-neutral-700">Tu Perfil</a>
                        <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-neutral-400 hover:text-white hover:bg-neutral-700">Configuración</a>
                        <form method="POST" action="{{ route('logout') ?? '#' }}">
                            @csrf
                            <a href="{{ route('logout') ?? '#' }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-3 py-2 rounded-md text-base font-medium text-neutral-400 hover:text-white hover:bg-neutral-700">
                                Cerrar Sesión
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex flex-1">
            <aside class="hidden sm:block bg-neutral-800 text-neutral-300 w-64 space-y-2 py-7 px-2 shadow-lg">
                <h3 class="px-4 text-lg font-semibold text-white">Navegación Secundaria</h3>
                <nav class="space-y-1">
                    <a href="#" class="block px-4 py-2.5 rounded-md text-sm font-medium hover:bg-neutral-700 hover:text-white">Enlace Secundario 1</a>
                    <a href="#" class="block px-4 py-2.5 rounded-md text-sm font-medium hover:bg-neutral-700 hover:text-white">Enlace Secundario 2</a>
                </nav>
            </aside>

            <main class="flex-1 p-4 sm:p-6 lg:p-8 bg-neutral-100">
                @if (isset($title))
                <header class="mb-6">
                    <h1 class="text-3xl font-bold text-neutral-900">
                        {{ $title }}
                    </h1>
                     {{-- Breadcrumbs Placeholder --}}
                </header>
                @endif

                {{ $slot }}
            </main>
        </div>

        <footer class="bg-neutral-900 text-neutral-300 text-center p-4 text-sm">
            Copyright &copy; {{ date('Y') }} {{ config('app.name', 'GimnasioApp') }}. Todos los derechos reservados.
            <p>Versión 1.0.0</p>
        </footer>
    </div>

    @livewireScripts
    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                mobileMenuButton.querySelectorAll('svg').forEach(icon => icon.classList.toggle('hidden'));
            });
        }

        // User menu toggle
        const userMenuButton = document.getElementById('user-menu-button');
        const userMenuDropdown = document.getElementById('user-menu-dropdown');
        if (userMenuButton && userMenuDropdown) {
            userMenuButton.addEventListener('click', (event) => {
                userMenuDropdown.classList.toggle('hidden');
                event.stopPropagation();
            });
            document.addEventListener('click', (event) => {
                if (!userMenuButton.contains(event.target) && !userMenuDropdown.contains(event.target)) {
                    userMenuDropdown.classList.add('hidden');
                }
            });
        }
    </script>
</body>
</html>
