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
    <header class="sticky top-0 z-40 bg-white/80 dark:bg-neutral-950/80 backdrop-blur-md border-b border-neutral-200 dark:border-neutral-800" x-data="{ mobileOpen: false }">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16" aria-label="{{ __('Navigation') }}">
            {{-- Logo --}}
            <a href="{{ route('home') }}" wire:navigate class="font-display font-bold text-xl text-primary-600 dark:text-primary-400 tracking-tight">
                AP
            </a>

            {{-- Desktop nav --}}
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('home') }}" wire:navigate class="text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors @if(request()->routeIs('home')) text-primary-600 dark:text-primary-400 @endif">
                    {{ __('Home') }}
                </a>
                <a href="{{ route('projects.index') }}" wire:navigate class="text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors @if(request()->routeIs('projects.*')) text-primary-600 dark:text-primary-400 @endif">
                    {{ __('Projects') }}
                </a>
                <a href="{{ route('blog.index') }}" wire:navigate class="text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors @if(request()->routeIs('blog.*')) text-primary-600 dark:text-primary-400 @endif">
                    {{ __('Blog') }}
                </a>
                <a href="{{ route('contact') }}" wire:navigate class="text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors @if(request()->routeIs('contact')) text-primary-600 dark:text-primary-400 @endif">
                    {{ __('Contact') }}
                </a>
            </div>

            {{-- Desktop actions --}}
            <div class="hidden md:flex items-center gap-3">
                {{-- Theme toggle --}}
                <button
                    x-data
                    @click="$store.theme.toggle()"
                    class="p-2 rounded-md text-neutral-500 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors"
                    :aria-label="$store.theme.dark ? '{{ __('Light Mode') }}' : '{{ __('Dark Mode') }}'"
                >
                    <x-svg-icon name="sun" class="w-5 h-5 hidden dark:block" />
                    <x-svg-icon name="moon" class="w-5 h-5 block dark:hidden" />
                </button>

                {{-- Language switcher --}}
                <div class="flex items-center gap-1 border border-neutral-200 dark:border-neutral-700 rounded-md p-0.5">
                    <a
                        href="{{ route('locale.switch', ['locale' => 'es']) }}"
                        class="px-2 py-1 text-xs font-medium rounded-sm transition-colors @if(app()->getLocale() === 'es') bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300 @else text-neutral-500 dark:text-neutral-400 hover:text-neutral-700 dark:hover:text-neutral-300 @endif"
                        aria-label="{{ __('Spanish') }}"
                    >ES</a>
                    <a
                        href="{{ route('locale.switch', ['locale' => 'en']) }}"
                        class="px-2 py-1 text-xs font-medium rounded-sm transition-colors @if(app()->getLocale() === 'en') bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300 @else text-neutral-500 dark:text-neutral-400 hover:text-neutral-700 dark:hover:text-neutral-300 @endif"
                        aria-label="{{ __('English') }}"
                    >EN</a>
                </div>
            </div>

            {{-- Mobile hamburger --}}
            <div class="flex md:hidden items-center gap-2">
                <button
                    x-data
                    @click="$store.theme.toggle()"
                    class="p-2 rounded-md text-neutral-500 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors"
                    :aria-label="$store.theme.dark ? '{{ __('Light Mode') }}' : '{{ __('Dark Mode') }}'"
                >
                    <x-svg-icon name="sun" class="w-4 h-4 hidden dark:block" />
                    <x-svg-icon name="moon" class="w-4 h-4 block dark:hidden" />
                </button>
                <button
                    @click="mobileOpen = !mobileOpen"
                    class="p-2 rounded-md text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors"
                    :aria-label="mobileOpen ? '{{ __('Close Menu') }}' : '{{ __('Open Menu') }}'"
                    aria-expanded="false"
                    x-bind:aria-expanded="mobileOpen"
                >
                    <x-svg-icon name="menu" class="w-5 h-5" x-show="!mobileOpen" />
                    <x-svg-icon name="x" class="w-5 h-5" x-show="mobileOpen" x-cloak />
                </button>
            </div>
        </nav>

        {{-- Mobile menu --}}
        <div
            x-show="mobileOpen"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            x-cloak
            class="md:hidden border-t border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-950"
        >
            <div class="px-4 py-4 space-y-3">
                <a href="{{ route('home') }}" wire:navigate @click="mobileOpen = false" class="block text-sm font-medium py-2 text-neutral-700 dark:text-neutral-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">{{ __('Home') }}</a>
                <a href="{{ route('projects.index') }}" wire:navigate @click="mobileOpen = false" class="block text-sm font-medium py-2 text-neutral-700 dark:text-neutral-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">{{ __('Projects') }}</a>
                <a href="{{ route('blog.index') }}" wire:navigate @click="mobileOpen = false" class="block text-sm font-medium py-2 text-neutral-700 dark:text-neutral-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">{{ __('Blog') }}</a>
                <a href="{{ route('contact') }}" wire:navigate @click="mobileOpen = false" class="block text-sm font-medium py-2 text-neutral-700 dark:text-neutral-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">{{ __('Contact') }}</a>
                <div class="flex items-center gap-2 pt-2">
                    <a href="{{ route('locale.switch', ['locale' => 'es']) }}" class="px-3 py-1 text-xs font-medium rounded-md border border-neutral-200 dark:border-neutral-700 @if(app()->getLocale() === 'es') bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300 border-primary-200 dark:border-primary-800 @else text-neutral-500 dark:text-neutral-400 @endif">ES</a>
                    <a href="{{ route('locale.switch', ['locale' => 'en']) }}" class="px-3 py-1 text-xs font-medium rounded-md border border-neutral-200 dark:border-neutral-700 @if(app()->getLocale() === 'en') bg-primary-100 dark:bg-primary-900/40 text-primary-700 dark:text-primary-300 border-primary-200 dark:border-primary-800 @else text-neutral-500 dark:text-neutral-400 @endif">EN</a>
                </div>
            </div>
        </div>
    </header>

    {{-- Main content --}}
    <main id="main-content" class="flex-1">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="bg-neutral-50 dark:bg-neutral-900 border-t border-neutral-200 dark:border-neutral-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Brand --}}
                <div>
                    <span class="font-display font-bold text-lg text-primary-600 dark:text-primary-400">{{ $profile?->full_name ?? 'Andrés Pinto' }}</span>
                    <p class="mt-2 text-sm text-neutral-500 dark:text-neutral-400">{{ __('Fullstack Developer & Software Engineer') }}</p>
                    <p class="mt-1 text-sm text-neutral-400 dark:text-neutral-500">{{ __('Chetumal, Quintana Roo, México') }}</p>
                </div>

                {{-- Contact info --}}
                <div>
                    <h3 class="font-display font-semibold text-sm text-neutral-900 dark:text-white mb-3">{{ __('Contact') }}</h3>
                    <ul class="space-y-2 text-sm text-neutral-500 dark:text-neutral-400">
                        <li class="flex items-center gap-2">
                            <x-svg-icon name="mail" class="w-4 h-4 text-neutral-400" />
                            <span>{{ $profile?->email ?? 'andrespintocamara@gmail.com' }}</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <x-svg-icon name="phone" class="w-4 h-4 text-neutral-400" />
                            <span>{{ $profile?->phone ?? '+52 983 135 4120' }}</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <x-svg-icon name="location" class="w-4 h-4 text-neutral-400" />
                            <span>{{ $profile?->location ?? __('Chetumal, Quintana Roo, México') }}</span>
                        </li>
                    </ul>
                </div>

                {{-- Social --}}
                <div>
                    <h3 class="font-display font-semibold text-sm text-neutral-900 dark:text-white mb-3">{{ __('Connect') }}</h3>
                    <div class="flex items-center gap-3">
                        <a href="#" class="p-2 rounded-md text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-200 hover:bg-neutral-200 dark:hover:bg-neutral-800 transition-colors" aria-label="GitHub">
                            <x-svg-icon name="github" class="w-5 h-5" />
                        </a>
                        <a href="#" class="p-2 rounded-md text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-200 hover:bg-neutral-200 dark:hover:bg-neutral-800 transition-colors" aria-label="LinkedIn">
                            <x-svg-icon name="linkedin" class="w-5 h-5" />
                        </a>
                        <a href="mailto:{{ $profile?->email ?? 'andrespintocamara@gmail.com' }}" class="p-2 rounded-md text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-200 hover:bg-neutral-200 dark:hover:bg-neutral-800 transition-colors" aria-label="Email">
                            <x-svg-icon name="mail" class="w-5 h-5" />
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-neutral-200 dark:border-neutral-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-neutral-400 dark:text-neutral-500">
                <p>&copy; {{ date('Y') }} Andrés Adrián Pinto Cámara. {{ __('All Rights Reserved') }}</p>
                <p>{{ __('Built with') }} Laravel & Livewire</p>
            </div>
        </div>
    </footer>

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