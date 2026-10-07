<button {{ $attributes->merge(['type' => 'button', 'class' => 'pill-outline text-sm']) }}>
    {{ $slot }}
</button>
