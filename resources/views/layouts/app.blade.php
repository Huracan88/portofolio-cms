<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $profile ??= \App\Models\Profile::getSingleton();
    @endphp

    <x-seo :title="$title ?? config('app.name', 'Andrés Pinto')" />

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="32x32">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    {{-- Neo theme: override global focus-visible to monochrome --}}
    <style>
        body :focus-visible {
            outline: none;
            --tw-ring-color: #ffffff;
            --tw-ring-offset-color: #121212;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-neo-bg text-neo-text font-sans overflow-x-clip">

    {{-- Skippy link --}}
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:top-4 focus:left-4 focus:px-4 focus:py-2 focus:bg-neo-text focus:text-neo-bg focus:border-2 focus:border-neo-text focus:shadow-neo-sm focus:outline-none">
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

    @livewireScripts
</body>
</html>
