<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $profile ??= \App\Models\Profile::getSingleton();
    @endphp

    <x-seo :title="$title ?? config('app.name', 'Andrés Pinto')" />

    {{-- Dark mode FOUC prevention --}}
    <script>
        (function () {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (saved === 'dark' || (!saved && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen flex flex-col bg-white dark:bg-neutral-950 text-neutral-800 dark:text-neutral-200 font-sans">

    {{-- Skippy link --}}
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:top-4 focus:left-4 focus:px-4 focus:py-2 focus:bg-primary-600 focus:text-white focus:rounded-md focus:outline-none">
        {{ __('Skip to content') }}
    </a>

    {{-- Header --}}
    @include('partials.header')

    {{-- Main content --}}
    <main id="main-content" class="flex-1">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    @include('partials.footer')

    {{-- Alpine theme store --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                dark: document.documentElement.classList.contains('dark'),

                toggle() {
                    this.dark = !this.dark;
                    localStorage.setItem('theme', this.dark ? 'dark' : 'light');
                    document.documentElement.classList.toggle('dark', this.dark);
                }
            });
        });

        document.addEventListener('livewire:navigated', () => {
            const store = Alpine.store('theme');
            const saved = localStorage.getItem('theme');
            const dark = store ? store.dark
                : saved === 'dark'
                || (!saved && window.matchMedia('(prefers-color-scheme: dark)').matches);

            document.documentElement.classList.toggle('dark', dark);
        });
    </script>

    @livewireScripts
</body>
</html>