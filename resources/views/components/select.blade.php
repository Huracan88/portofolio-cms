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
        <label for="{{ $name }}" class="block mb-1.5 font-mono text-[10px] font-bold uppercase tracking-widest text-neo-muted">
            {{ $label }}
            @if ($required)
                <span class="text-neo-text" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    <select
        id="{{ $name }}"
        name="{{ $name }}"
        @if ($required) required @endif
        {{ $attributes->merge(['class' => 'block w-full border-2 border-neo-line bg-neo-panel px-3 py-2.5 text-sm text-neo-text transition-colors duration-150 focus:border-neo-text focus:ring-2 focus:ring-neo-text/30 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed']) }}
    >
        @foreach ($options as $optionLabel => $optionValue)
            <option value="{{ $optionValue }}" @selected(old($name, $value ?? '') == $optionValue)>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>

    @if ($error)
        <p class="mt-1.5 font-mono text-[10px] font-bold uppercase tracking-widest text-neo-text">{{ $error }}</p>
    @endif
</div>
