<button {{ $attributes->merge(['type' => 'submit', 'class' => 'pill pill-danger text-sm']) }}>
    {{ $slot }}
</button>
