<button {{ $attributes->merge(['type' => 'submit', 'class' => 'pill text-sm']) }}>
    {{ $slot }}
</button>
