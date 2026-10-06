@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'input-ruang border border-[#e5e5e5] rounded px-3 py-2 text-sm text-[#242424] focus:border-[#191919] focus:ring-0 outline-none']) }}>
