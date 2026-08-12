<div>
    @section('meta_description', __('Have a project in mind or just want to say hello? Fill out the form below and I\'ll get back to you as soon as possible.'))
    <x-section :title="__('GET IN TOUCH')" :eyebrow="__('CONTACT')" align="center">
        <p class="max-w-xl mx-auto mt-4 text-center text-neo-muted">
            {{ __('Have a project in mind or just want to say hello? Fill out the form below and I\'ll get back to you as soon as possible.') }}
        </p>
    </x-section>

    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        @if ($sent)
            <x-card class="text-center py-10">
                <div class="flex h-12 w-12 items-center justify-center border-2 border-neo-text bg-neo-panel-deep mx-auto mb-4">
                    <x-svg-icon name="check" class="w-6 h-6 text-neo-text" />
                </div>
                <h2 class="font-display-heavy text-xl uppercase text-neo-text mb-2">{{ __('MESSAGE SENT') }}</h2>
                <p class="text-neo-muted">{{ __('Thank you for reaching out. I\'ll get back to you soon.') }}</p>
                <x-button variant="ghost" size="sm" wire:click="$set('sent', false)" class="mt-6">
                    {{ __('SEND ANOTHER MESSAGE') }}
                </x-button>
            </x-card>
        @else
            <x-card>
                <form wire:submit="submit" class="space-y-5" novalidate>
                    <div class="absolute opacity-0 pointer-events-none" style="height:0;overflow:hidden" aria-hidden="true">
                        <label for="website">{{ __('Website') }}</label>
                        <input type="text" id="website" name="website" wire:model="website" tabindex="-1" autocomplete="off">
                    </div>

                    <x-input
                        name="name"
                        :label="__('Full Name')"
                        :placeholder="__('Your Name')"
                        :required="true"
                        wire:model="name"
                        :error="$errors->first('name')"
                    />

                    <x-input
                        name="email"
                        type="email"
                        :label="__('Email Address')"
                        :placeholder="__('Your Email')"
                        :required="true"
                        wire:model="email"
                        :error="$errors->first('email')"
                    />

                    <x-input
                        name="subject"
                        :label="__('Subject')"
                        :placeholder="__('Subject')"
                        wire:model="subject"
                        :error="$errors->first('subject')"
                    />

                    <x-textarea
                        name="message"
                        :label="__('Message')"
                        :placeholder="__('Your message')"
                        :required="true"
                        rows="5"
                        wire:model="message"
                        :error="$errors->first('message')"
                    />

                    <div class="pt-2">
                        <x-button type="submit" variant="primary" size="lg" class="w-full sm:w-auto" icon="send">
                            {{ __('SEND MESSAGE') }}
                        </x-button>
                    </div>
                </form>
            </x-card>
        @endif
    </div>
</div>
