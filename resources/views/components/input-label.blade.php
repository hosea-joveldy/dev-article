@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-xs text-[#242424] mb-1.5']) }}>
    {{ $value ?? $slot }}
</label>
