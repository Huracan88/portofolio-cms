@props([
    'name',
    'label' => null,
    'error' => null,
    'required' => false,
    'options' => [],
    'value' => null,
])

<div>
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">
            {{ $label }}
            @if ($required)
                <span class="text-danger-500" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    <select
        id="{{ $name }}"
        name="{{ $name }}"
        @if ($required) required @endif
        {{ $attributes->merge(['class' => 'block w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 px-3 py-2 text-sm text-neutral-900 dark:text-neutral-100 shadow-sm transition-colors duration-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 disabled:opacity-50 disabled:cursor-not-allowed']) }}
    >
        @foreach ($options as $optionLabel => $optionValue)
            <option value="{{ $optionValue }}" @selected(old($name, $value ?? '') == $optionValue)>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>

    @if ($error)
        <p class="mt-1.5 text-sm text-danger-500">{{ $error }}</p>
    @endif
</div>
