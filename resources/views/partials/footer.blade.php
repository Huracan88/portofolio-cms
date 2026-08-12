{{-- Shared footer (default layout) --}}
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
