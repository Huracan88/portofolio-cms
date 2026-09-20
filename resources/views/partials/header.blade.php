{{-- Neo-Brutalist header (default layout) --}}
<header class="sticky top-0 z-40 bg-neo-bg/95 backdrop-blur border-b-2 border-neo-text" x-data="{ mobileOpen: false }">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16" aria-label="{{ __('Navigation') }}">
        {{-- Logo --}}
        <a href="{{ route('home') }}" wire:navigate class="group inline-flex items-center gap-2.5">
            <span class="flex h-9 w-9 items-center justify-center bg-neo-text text-neo-bg font-display-heavy text-lg shadow-neo-sm transition-all duration-150 group-hover:-translate-x-0.5 group-hover:-translate-y-0.5 active:translate-x-0 active:translate-y-0 active:shadow-none">
                AP
            </span>
            <span class="hidden sm:block font-pixel text-[9px] tracking-widest text-neo-muted">{{ __('MODE: BUILD') }}</span>
        </a>

        {{-- Desktop nav --}}
        <div class="hidden md:flex items-center gap-1">
            @foreach ([
                ['label' => __('Home'), 'route' => 'home', 'active' => request()->routeIs('home')],
                ['label' => __('Projects'), 'route' => 'projects.index', 'active' => request()->routeIs('projects.*')],
                ['label' => __('Blog'), 'route' => 'blog.index', 'active' => request()->routeIs('blog.*')],
                ['label' => __('Contact'), 'route' => 'contact', 'active' => request()->routeIs('contact')],
            ] as $item)
                <a
                    href="{{ route($item['route']) }}"
                    wire:navigate
                    class="px-3 py-2 font-mono text-xs font-bold uppercase tracking-wider border-2 transition-all duration-150 {{ $item['active'] ? 'bg-neo-text text-neo-bg border-neo-text shadow-neo-sm' : 'border-transparent text-neo-muted hover:border-neo-text hover:text-neo-text hover:-translate-y-0.5 hover:bg-neo-panel' }}"
                >
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>

        {{-- Desktop actions --}}
        <div class="hidden md:flex items-center gap-3">
            {{-- Language switcher --}}
            <div class="flex items-center border-2 border-neo-text shadow-neo-sm">
                <a
                    href="{{ route('locale.switch', ['locale' => 'es']) }}"
                    class="px-2.5 py-1.5 font-mono text-xs font-bold {{ app()->getLocale() === 'es' ? 'bg-neo-text text-neo-bg' : 'text-neo-muted hover:bg-neo-panel hover:text-neo-text' }}"
                    aria-label="{{ __('Spanish') }}"
                >ES</a>
                <a
                    href="{{ route('locale.switch', ['locale' => 'en']) }}"
                    class="px-2.5 py-1.5 font-mono text-xs font-bold border-l-2 border-neo-text {{ app()->getLocale() === 'en' ? 'bg-neo-text text-neo-bg' : 'text-neo-muted hover:bg-neo-panel hover:text-neo-text' }}"
                    aria-label="{{ __('English') }}"
                >EN</a>
            </div>

            {{-- CTA --}}
            <a
                href="{{ route('contact') }}"
                wire:navigate
                class="inline-flex items-center gap-2 px-4 py-2 font-mono text-xs font-bold uppercase tracking-wider bg-neo-text text-neo-bg border-2 border-neo-text shadow-neo-sm transition-all duration-150 hover:-translate-x-0.5 hover:-translate-y-0.5 active:translate-x-0 active:translate-y-0 active:shadow-none"
            >
                {{ __('HIRE ME') }}
            </a>
        </div>

        {{-- Mobile hamburger --}}
        <div class="flex md:hidden items-center">
            <button
                @click="mobileOpen = !mobileOpen"
                class="p-2 border-2 border-neo-text text-neo-text shadow-neo-sm transition-all duration-150 active:translate-x-0 active:translate-y-0 active:shadow-none"
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
        class="md:hidden border-t-2 border-neo-text bg-neo-bg"
    >
        <div class="px-4 py-4 space-y-2">
            @foreach ([
                ['label' => __('Home'), 'route' => 'home', 'active' => request()->routeIs('home')],
                ['label' => __('Projects'), 'route' => 'projects.index', 'active' => request()->routeIs('projects.*')],
                ['label' => __('Blog'), 'route' => 'blog.index', 'active' => request()->routeIs('blog.*')],
                ['label' => __('Contact'), 'route' => 'contact', 'active' => request()->routeIs('contact')],
            ] as $item)
                <a
                    href="{{ route($item['route']) }}"
                    wire:navigate
                    @click="mobileOpen = false"
                    class="block px-3 py-2.5 font-mono text-xs font-bold uppercase tracking-wider transition-all duration-150 border-2 {{ ($item['active'] ?? false) ? 'bg-neo-text text-neo-bg border-neo-text shadow-neo-sm' : 'border-transparent text-neo-muted hover:border-neo-text hover:text-neo-text hover:bg-neo-panel' }}"
                >
                    ▶ {{ $item['label'] }}
                </a>
            @endforeach

            <div class="pt-3 flex flex-col gap-3 border-t-2 border-neo-panel">
                {{-- Mobile CTA --}}
                <a
                    href="{{ route('contact') }}"
                    wire:navigate
                    @click="mobileOpen = false"
                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 font-mono text-xs font-bold uppercase tracking-wider bg-neo-text text-neo-bg border-2 border-neo-text shadow-neo-sm active:translate-x-0 active:translate-y-0 active:shadow-none"
                >
                    {{ __('HIRE ME') }} →
                </a>

                {{-- Language switch --}}
                <div class="flex items-center gap-2">
                    <span class="font-mono text-[10px] tracking-widest text-neo-muted uppercase">// {{ __('Language') }}:</span>
                    <div class="flex items-center border-2 border-neo-text shadow-neo-sm">
                        <a href="{{ route('locale.switch', ['locale' => 'es']) }}" class="px-3 py-1.5 font-mono text-xs font-bold {{ app()->getLocale() === 'es' ? 'bg-neo-text text-neo-bg' : 'text-neo-muted hover:bg-neo-panel hover:text-neo-text' }}">ES</a>
                        <a href="{{ route('locale.switch', ['locale' => 'en']) }}" class="px-3 py-1.5 font-mono text-xs font-bold border-l-2 border-neo-text {{ app()->getLocale() === 'en' ? 'bg-neo-text text-neo-bg' : 'text-neo-muted hover:bg-neo-panel hover:text-neo-text' }}">EN</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>