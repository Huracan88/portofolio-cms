@props([
    'title' => 'Andrés Pinto',
])

@php
    $profile = \App\Models\Profile::getSingleton();
    $siteName = $profile?->full_name ?: 'Andrés Pinto';
    $defaultDescription = __('Senior Fullstack Developer & Software Engineer — 17+ years building custom web solutions.');

    $metaDescription = trim((string) $__env->yieldContent('meta_description')) ?: $defaultDescription;
    $metaImage = trim((string) $__env->yieldContent('meta_image')) ?: null;
    $metaType = trim((string) $__env->yieldContent('meta_type')) ?: 'website';

    $imageUrl = $metaImage ? (filter_var($metaImage, FILTER_VALIDATE_URL) ? $metaImage : asset($metaImage)) : null;
@endphp

<title>{{ $title }}</title>
<meta name="description" content="{{ $metaDescription }}">

<link rel="canonical" href="{{ url()->current() }}">

<link rel="alternate" hreflang="{{ app()->getLocale() === 'es' ? 'en' : 'es' }}" href="{{ url()->current() }}?lang={{ app()->getLocale() === 'es' ? 'en' : 'es' }}">

<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:type" content="{{ $metaType }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:locale" content="{{ app()->getLocale() }}">
<meta property="og:site_name" content="{{ $siteName }}">
@if($imageUrl)
<meta property="og:image" content="{{ $imageUrl }}">
@endif

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $metaDescription }}">
@if($imageUrl)
<meta name="twitter:image" content="{{ $imageUrl }}">
@endif