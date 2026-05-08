<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Movie Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'sky-primary': '#0ea5e9',
                        'sky-light': '#38bdf8',
                        'dark-bg': '#0f172a',
                        'dark-card': '#1e293b',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }
    </style>
</head>
<body class="bg-dark-bg text-white min-h-screen">
    <!-- Navigation -->
    <nav class="bg-dark-card shadow-lg border-b border-sky-primary/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('media.index') }}" class="text-2xl font-bold text-sky-primary">
                        Movie Dashboard
                    </a>
                    <div class="hidden md:flex ml-10 space-x-4">
                        <a href="{{ route('media.index') }}"
                           class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('media.*') ? 'bg-sky-primary text-white' : 'text-gray-300 hover:bg-dark-bg hover:text-white' }}">
                            Médias
                        </a>
                        <a href="{{ route('categories.index') }}"
                           class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('categories.*') ? 'bg-sky-primary text-white' : 'text-gray-300 hover:bg-dark-bg hover:text-white' }}">
                            Catégories
                        </a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('media.create') }}"
                       class="bg-sky-primary hover:bg-sky-light text-white px-4 py-2 rounded-md text-sm font-medium transition">
                        + Ajouter un média
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-500/20 border border-green-500 text-green-100 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-500/20 border border-red-500 text-red-100 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark-card mt-16 py-6 border-t border-sky-primary/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-gray-400">
            <p>&copy; {{ date('Y') }} Movie Dashboard. Tous droits réservés.</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
