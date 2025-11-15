<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr" class="bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-base-url" content="{{ url('/api') }}">
    <meta name="recipe-categories" content='@json(config('recipes.categories'))'>

    <title>{{ config('app.name', 'Blessed Cafe') }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#512E1B',
                        secondary: '#895737',
                    },
                },
            },
        };
    </script>
    @auth
        <script>
            window.__appUser = @js([
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'role' => auth()->user()->role,
            ]);
        </script>
    @endauth
    <style>
        [x-cloak] { display: none !important; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@php
    $pageKey = trim($__env->yieldContent('page_key', 'pos'));
@endphp
<body x-data="app()" :dir="direction" :class="{'rtl': direction === 'rtl'}"
      x-init="init('{{ $pageKey }}')" class="min-h-screen bg-gray-50 text-gray-900">
    @auth
    <header class="bg-primary text-white shadow-md">
        <div class="container mx-auto px-4 py-3 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center gap-3">
                <h1 class="text-xl font-bold flex items-center gap-2">
                    <i class="fas fa-utensils"></i>
                    <span x-text="settings.restaurantName || translations.appTitle"></span>
                </h1>
                <!-- <span class="hidden md:inline text-sm text-white/70">
                    {{ config('app.name', 'Blessed Cafe Suite') }}
                </span> -->
            </div>

            <div class="flex flex-col gap-3 md:flex-row md:items-center md:gap-6">
                <nav class="flex flex-wrap gap-2 md:gap-4">
                    <a href="{{ route('pos') }}" x-show="hasRole(['admin','cashier'])"
                       @class([
                           'px-3 py-1 rounded-md transition flex items-center gap-2',
                           'bg-white text-primary' => request()->routeIs('pos'),
                           'hover:bg-white hover:text-primary' => !request()->routeIs('pos'),
                       ])>
                        <i class="fas fa-cash-register"></i>
                        <span x-text="translations.pos"></span>
                    </a>
                    <a href="{{ route('kds') }}" x-show="hasRole(['admin','kitchen'])"
                       @class([
                           'px-3 py-1 rounded-md transition flex items-center gap-2',
                           'bg-white text-primary' => request()->routeIs('kds'),
                           'hover:bg-white hover:text-primary' => !request()->routeIs('kds'),
                       ])>
                        <i class="fas fa-tv"></i>
                        <span x-text="translations.kds"></span>
                    </a>
                    <a href="{{ route('recipes') }}" x-show="hasRole(['admin','kitchen'])"
                       @class([
                           'px-3 py-1 rounded-md transition flex items-center gap-2',
                           'bg-white text-primary' => request()->routeIs('recipes'),
                           'hover:bg-white hover:text-primary' => !request()->routeIs('recipes'),
                       ])>
                        <i class="fas fa-book"></i>
                        <span x-text="translations.recipes"></span>
                    </a>
                    <a href="{{ route('tables') }}" x-show="hasRole(['admin'])"
                       @class([
                           'px-3 py-1 rounded-md transition flex items-center gap-2',
                           'bg-white text-primary' => request()->routeIs('tables'),
                           'hover:bg-white hover:text-primary' => !request()->routeIs('tables'),
                       ])>
                        <i class="fas fa-chair"></i>
                        <span x-text="translations.tables"></span>
                    </a>
                    <a href="{{ route('reports') }}" x-show="hasRole(['admin'])"
                       @class([
                           'px-3 py-1 rounded-md transition flex items-center gap-2',
                           'bg-white text-primary' => request()->routeIs('reports'),
                           'hover:bg-white hover:text-primary' => !request()->routeIs('reports'),
                       ])>
                        <i class="fas fa-chart-bar"></i>
                        <span x-text="translations.reports"></span>
                    </a>
                    <a href="{{ route('inventory') }}" x-show="hasRole(['admin','inventory'])"
                       @class([
                           'px-3 py-1 rounded-md transition flex items-center gap-2',
                           'bg-white text-primary' => request()->routeIs('inventory'),
                           'hover:bg-white hover:text-primary' => !request()->routeIs('inventory'),
                       ])>
                        <i class="fas fa-boxes"></i>
                        <span x-text="translations.inventory"></span>
                    </a>
                </nav>

                <div class="flex items-center gap-2 md:gap-3">
                    <button @click="showBackup = true" x-show="hasRole(['admin'])"
                            class="px-3 py-1 rounded-md hover:bg-white hover:text-primary transition flex items-center gap-2">
                        <i class="fas fa-database"></i>
                        <span class="hidden md:inline">{{ __('Backup') }}</span>
                    </button>
                    <button @click="showSettings = true" x-show="hasRole(['admin'])"
                            class="px-3 py-1 rounded-md hover:bg-white hover:text-primary transition flex items-center gap-2">
                        <i class="fas fa-cog"></i>
                        <span class="hidden md:inline" x-text="translations.settings"></span>
                    </button>
                </div>
            </div>

            <div class="flex flex-col-reverse gap-2 md:flex-row md:items-center md:gap-4">
                <div class="text-sm text-white/90">
                    <div class="font-semibold">{{ auth()->user()->name }}</div>
                    <div class="text-white/70 capitalize">{{ str_replace('_', ' ', auth()->user()->role) }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="px-4 py-2 bg-white text-primary rounded-md text-sm font-semibold hover:bg-gray-100 transition">
                        {{ __('Logout') }}
                    </button>
                </form>
            </div>
        </div>
    </header>
    @endauth

    <main class="container mx-auto px-4 py-6">
        @yield('content')
    </main>

    @auth
        @include('partials.modals')
    @endauth

    @stack('scripts')
</body>
</html>
