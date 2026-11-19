@props(['variant' => 'dark'])

@php
    $circleFill = $variant === 'light' ? '#FFFFFF' : '#4F46E5';
    $crescentFill = $variant === 'light' ? '#A5B4FC' : '#A5B4FC';
    $textColor = $variant === 'light' ? 'text-white' : 'text-slate-900';
    $subtextColor = $variant === 'light' ? 'text-indigo-300' : 'text-indigo-600';
    $maskId = 'retailify-logo-mask-' . uniqid();
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex items-center space-x-2.5']) }}>
    <svg class="w-8 h-7 shrink-0" viewBox="0 0 36 28" fill="none" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <mask id="{{ $maskId }}">
                <rect width="36" height="28" fill="white"/>
                <circle cx="13.5" cy="14" r="14.5" fill="black"/>
            </mask>
        </defs>
        <circle cx="21" cy="14" r="11" fill="{{ $crescentFill }}" mask="url(#{{ $maskId }})"/>
        <circle cx="13.5" cy="14" r="12.5" fill="{{ $circleFill }}"/>
    </svg>
    <div class="text-left">
        <span class="text-lg font-extrabold tracking-tight {{ $textColor }} block leading-none">Retailify</span>
        <span class="text-[10px] font-semibold {{ $subtextColor }} uppercase tracking-wider block mt-0.5">Smart POS</span>
    </div>
</div>