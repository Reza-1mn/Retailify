@props(['variant' => 'auto'])

<div class="inline-flex items-center space-x-2.5 select-none">
    <!-- Ikon Dua Lingkaran Bertumpuk -->
    <div class="relative w-8 h-8 flex items-center justify-center shrink-0">
        <span class="w-6 h-6 rounded-full translate-x-1 {{ $variant === 'light' ? 'retailify-circle-back-light' : 'retailify-circle-back' }}"
              style="background-color: {{ $variant === 'light' ? '#93a5fd' : 'rgba(129, 140, 248, 0.75)' }};"></span>
        <span class="w-6 h-6 rounded-full -translate-x-1 absolute {{ $variant === 'light' ? 'retailify-circle-front-light' : 'retailify-circle-front' }}"
              style="background-color: {{ $variant === 'light' ? '#ffffff' : '#4f46e5' }};"></span>
    </div>

    <div class="leading-tight">
        @if($variant === 'light')
            <span class="block text-base font-extrabold tracking-tight" style="color: #FFFFFF;">
                Retailify
            </span>
            <span class="block text-[9px] font-extrabold tracking-widest uppercase" style="color: #C7D2FE;">
                SMART POS
            </span>
        @else
            <span class="retailify-logo-title block text-base font-extrabold tracking-tight" style="color: #0f172a;">
                Retailify
            </span>
            <span class="retailify-logo-sub block text-[9px] font-extrabold tracking-widest uppercase" style="color: #4f46e5;">
                SMART POS
            </span>
        @endif
    </div>
</div>