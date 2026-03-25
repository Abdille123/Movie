<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'ReelRoute')</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=bricolage-grotesque:400,500,700,800|space-grotesk:400,500,700" rel="stylesheet" />
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
        @stack('head')
    </head>
    <body>
        <div class="site-shell">
            <header class="site-header">
                <a class="brand" href="{{ route('home') }}">
                    <span class="brand-mark">RR</span>
                    <span>
                        <strong>ReelRoute</strong>
                        <small>Movie night planner</small>
                    </span>
                </a>

                <nav class="site-nav">
                    <a href="{{ route('home') }}" @class(['is-active' => request()->routeIs('home')])>Home</a>
                    <a href="{{ route('movies.index') }}" @class(['is-active' => request()->routeIs('movies.*')])>Movies</a>
                </nav>

                <div class="planner-pill">
                    <span>Shortlist</span>
                    <strong data-planner-count>0</strong>
                </div>
            </header>

            @if (session('status'))
                <div class="flash-banner">{{ session('status') }}</div>
            @endif

            <main class="page-frame">
                @yield('content')
            </main>

            <footer class="site-footer"></footer>
        </div>

        @stack('scripts')
    </body>
</html>
