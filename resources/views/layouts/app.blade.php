<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>AI Image Processing</title>

    <script>
        (function () {
            try {
                var storedTheme = localStorage.getItem('theme');
                var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                var theme = storedTheme || (prefersDark ? 'dark' : 'light');
                document.documentElement.dataset.theme = theme;
                document.documentElement.style.colorScheme = theme;
            } catch (error) {
                document.documentElement.dataset.theme = 'dark';
                document.documentElement.style.colorScheme = 'dark';
            }
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">

    @php
        $navItems = [
            ['label' => 'Home', 'route' => 'home', 'active' => request()->routeIs('home')],
            ['label' => 'Basics', 'route' => 'basics', 'active' => request()->routeIs('basics')],
            ['label' => 'Histogram', 'route' => ['tools', ['slug' => 'histogram']], 'active' => request()->routeIs('tools') && request()->route('slug') === 'histogram'],
            ['label' => 'Noise', 'route' => ['tools', ['slug' => 'noise']], 'active' => request()->routeIs('tools') && request()->route('slug') === 'noise'],
            ['label' => 'Filtering', 'route' => ['tools', ['slug' => 'filtering']], 'active' => request()->routeIs('tools') && request()->route('slug') === 'filtering'],
            ['label' => 'Edge Detection', 'route' => ['tools', ['slug' => 'edges']], 'active' => request()->routeIs('tools') && request()->route('slug') === 'edges'],
            ['label' => 'Segmentation', 'route' => ['tools', ['slug' => 'segmentation']], 'active' => request()->routeIs('tools') && request()->route('slug') === 'segmentation'],
            ['label' => 'Morphology', 'route' => ['tools', ['slug' => 'morphology']], 'active' => request()->routeIs('tools') && request()->route('slug') === 'morphology'],
            ['label' => 'Evaluation', 'route' => ['tools', ['slug' => 'evaluation']], 'active' => request()->routeIs('tools') && request()->route('slug') === 'evaluation'],
            ['label' => 'Help', 'route' => 'help', 'active' => request()->routeIs('help')],
        ];
    @endphp

    <header class="sticky top-0 z-50 border-b border-white/10 bg-slate-950/80 backdrop-blur-xl">
        <div class="mx-auto flex w-full max-w-7xl flex-col gap-3 px-4 py-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('home') }}" class="flex items-center gap-3 text-white transition hover:opacity-90">
                    <span class="font-display text-sm font-bold uppercase tracking-[0.28em] text-slate-100 sm:text-base">Image Processing</span>
                </a>

                <div class="flex items-center gap-3">
                    <span class="hidden rounded-full border border-cyan-400/20 bg-cyan-400/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.28em] text-cyan-200 sm:inline-flex">
                        Laravel + Python
                    </span>

                    <button
                        type="button"
                        class="theme-toggle flex h-11 w-11 items-center justify-center rounded-full border border-white/10 bg-white/5 text-slate-100 transition hover:border-cyan-300/40 hover:bg-white/10"
                        data-theme-toggle
                        aria-label="Toggle dark and light mode"
                        aria-pressed="false"
                        title="Toggle dark and light mode"
                    >
                        <span class="theme-toggle-icon text-base" data-theme-icon>☾</span>
                    </button>
                </div>
            </div>

            <nav class="-mx-1 flex gap-2 overflow-x-auto pb-1 pt-1 text-sm scrollbar-thin scrollbar-thumb-slate-700 scrollbar-track-transparent">
                @foreach ($navItems as $item)
                    @php
                        $href = is_array($item['route']) ? route($item['route'][0], $item['route'][1]) : route($item['route']);
                    @endphp
                    <a
                        href="{{ $href }}"
                        class="whitespace-nowrap rounded-full border px-4 py-2 transition duration-200 {{ $item['active'] ? 'border-cyan-300/50 bg-cyan-400/15 text-cyan-100 shadow-[0_0_0_1px_rgba(34,211,238,0.12)]' : 'border-white/10 bg-white/5 text-slate-300 hover:border-cyan-300/30 hover:bg-white/10 hover:text-white' }}"
                    >
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>
        </div>
    </header>

    @yield('content')

</body>
</html>
