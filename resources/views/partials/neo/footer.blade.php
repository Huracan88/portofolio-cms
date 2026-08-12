{{-- Neo-Brutalist footer (home-3 / layouts.neo) --}}
<footer class="bg-neo-bg border-t-2 border-neo-text">
    {{-- Marquee tech strip --}}
    @php
        $ticker = ['LARAVEL', 'LIVEWIRE', 'FILAMENT', 'TAILWIND', 'MARIADB', 'DOCKER', 'PHP', 'PEST', 'VITE'];
    @endphp
    <div class="overflow-hidden border-b-2 border-neo-text bg-neo-panel py-2.5" aria-hidden="true">
        <div class="flex w-max animate-marquee">
            <div class="flex shrink-0 items-center">
                @foreach ($ticker as $tag)
                    <span class="px-4 font-mono text-[11px] font-bold tracking-[0.2em] text-neo-text">[ {{ $tag }} ]</span>
                @endforeach
            </div>
            <div class="flex shrink-0 items-center">
                @foreach ($ticker as $tag)
                    <span class="px-4 font-mono text-[11px] font-bold tracking-[0.2em] text-neo-text">[ {{ $tag }} ]</span>
                @endforeach
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            {{-- Brand --}}
            <div>
                <span class="inline-flex items-center gap-2.5">
                    <span class="flex h-9 w-9 items-center justify-center bg-neo-text text-neo-bg font-display-heavy text-lg shadow-neo-sm">AP</span>
                    <span class="font-pixel text-[9px] tracking-widest text-neo-muted">// SYSTEM.INFO</span>
                </span>
                <p class="mt-4 text-sm text-neo-muted">{{ $profile?->title ?? __('Fullstack Developer & Software Engineer') }}</p>
                <p class="mt-2 flex items-center gap-1.5 text-sm text-neo-muted/80">
                    <x-svg-icon name="location" class="w-4 h-4 shrink-0" />
                    {{ $profile?->location ?? __('Chetumal, Quintana Roo, México') }}
                </p>
            </div>

            {{-- Contact info --}}
            <div>
                <h3 class="mb-4 font-mono text-xs font-bold uppercase tracking-widest text-neo-text">[ {{ __('Contact') }} ]</h3>
                <ul class="space-y-2.5 text-sm text-neo-muted">
                    <li class="flex items-center gap-2">
                        <x-svg-icon name="mail" class="w-4 h-4 shrink-0" />
                        <a href="mailto:{{ $profile?->email ?? 'andrespintocamara@gmail.com' }}" class="transition-colors hover:text-neo-text hover:underline">{{ $profile?->email ?? 'andrespintocamara@gmail.com' }}</a>
                    </li>
                    <li class="flex items-center gap-2">
                        <x-svg-icon name="phone" class="w-4 h-4 shrink-0" />
                        <span>{{ $profile?->phone ?? '+52 983 135 4120' }}</span>
                    </li>
                    @if ($profile?->license_number)
                        <li class="flex items-center gap-2">
                            <x-svg-icon name="graduation-cap" class="w-4 h-4 shrink-0" />
                            <span>{{ __('License') }}: {{ $profile->license_number }}</span>
                        </li>
                    @endif
                </ul>
            </div>

            {{-- Social / quick contact --}}
            <div>
                <h3 class="mb-4 font-mono text-xs font-bold uppercase tracking-widest text-neo-text">[ {{ __('Connect') }} ]</h3>
                @php
                    $social = $profile?->social_links ?? [];
                @endphp
                <div class="flex flex-wrap gap-3">
                    <a href="{{ $social['github'] ?? '#' }}" target="_blank" rel="noopener noreferrer" aria-label="GitHub" class="flex h-11 w-11 items-center justify-center border-2 border-neo-text bg-neo-panel text-neo-text shadow-neo-sm transition-all duration-150 hover:-translate-x-1 hover:-translate-y-1 hover:bg-neo-text hover:text-neo-bg active:translate-x-0 active:translate-y-0 active:shadow-none">
                        <x-svg-icon name="github" class="w-5 h-5" />
                    </a>
                    <a href="{{ $social['linkedin'] ?? '#' }}" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn" class="flex h-11 w-11 items-center justify-center border-2 border-neo-text bg-neo-panel text-neo-text shadow-neo-sm transition-all duration-150 hover:-translate-x-1 hover:-translate-y-1 hover:bg-neo-text hover:text-neo-bg active:translate-x-0 active:translate-y-0 active:shadow-none">
                        <x-svg-icon name="linkedin" class="w-5 h-5" />
                    </a>
                    <a href="mailto:{{ $profile?->email ?? 'andrespintocamara@gmail.com' }}" aria-label="Email" class="flex h-11 w-11 items-center justify-center border-2 border-neo-text bg-neo-panel text-neo-text shadow-neo-sm transition-all duration-150 hover:-translate-x-1 hover:-translate-y-1 hover:bg-neo-text hover:text-neo-bg active:translate-x-0 active:translate-y-0 active:shadow-none">
                        <x-svg-icon name="mail" class="w-5 h-5" />
                    </a>
                    <a href="{{ route('contact') }}" wire:navigate aria-label="{{ __('Contact') }}" class="flex h-11 w-11 items-center justify-center border-2 border-neo-text bg-neo-text text-neo-bg shadow-neo-sm transition-all duration-150 hover:-translate-x-1 hover:-translate-y-1 active:translate-x-0 active:translate-y-0 active:shadow-none">
                        <x-svg-icon name="send" class="w-5 h-5" />
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-10 flex flex-col sm:flex-row items-center justify-between gap-4 border-t-2 border-neo-text pt-6 font-mono text-[10px] uppercase tracking-widest text-neo-muted">
            <p>&copy; {{ date('Y') }} {{ $profile?->full_name ?? 'Andrés Adrián Pinto Cámara' }}.</p>
            <p class="flex items-center gap-2">BUILT WITH <span class="text-neo-text">LARAVEL</span> + <span class="text-neo-text">LIVEWIRE</span></p>
            <a href="#main-content" class="border-2 border-neo-text px-3 py-1 text-neo-text transition-colors hover:bg-neo-text hover:text-neo-bg">
                TOP ▲
            </a>
        </div>
    </div>
</footer>
