@props(['icon' => 'auto', 'name' => '', 'class' => 'w-4 h-4'])

@php
    $resolvedIcon = \App\Helpers\CategoryIconHelper::resolve($icon, $name);
@endphp

@switch($resolvedIcon)
    @case('skincare')
        <svg {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3.25C12 3.25 5.5 10.6 5.5 15a6.5 6.5 0 0013 0c0-4.4-6.5-11.75-6.5-11.75z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.5 15.5a2.5 2.5 0 002.5 2.5"/>
        </svg>
        @break
    @case('hair')
        <svg {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <circle cx="6" cy="6" r="3"/>
            <circle cx="6" cy="18" r="3"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M20 4L8.12 15.88M14.47 14.48L20 20M8.12 8.12L12 12"/>
        </svg>
        @break
    @case('health')
        <svg {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <rect x="4" y="4" width="16" height="16" rx="4"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v8m-4-4h8"/>
        </svg>
        @break
    @case('baby')
        <svg {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 20s-7-4.43-7-10.2A4.2 4.2 0 0112 6.6a4.2 4.2 0 017 3.2C19 15.57 12 20 12 20z"/>
        </svg>
        @break
    @case('rice')
        <svg {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4a8 8 0 0016 0zM12 12v8m-4 0h8"/>
        </svg>
        @break
    @case('home')
        <svg {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5L12 3l9 7.5V20a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1v-9.5z"/>
        </svg>
        @break
    @case('sparkles')
        <svg {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.29 6.71L22 13l-6.71 2.29L13 22l-2.29-6.71L4 13l6.71-2.29L13 3z"/>
        </svg>
        @break
    @case('flame')
        <svg {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.5 3 4 5.2 4 8a4 4 0 11-8 0c0-1.6.6-3.1 1.6-4.5.3 1.4 1.1 2.3 2.4 2.5C11.4 7.3 11.5 5 12 3z"/>
        </svg>
        @break
    @case('milk')
        <svg {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 3h8l-1 4 2 3v11H7V10l2-3-1-4z"/>
        </svg>
        @break
    @case('bread')
        <svg {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 11a4 4 0 014-4h8a4 4 0 014 4v1H4v-1zm1 1h14v7a1 1 0 01-1 1H6a1 1 0 01-1-1v-7z"/>
        </svg>
        @break
    @case('pencil')
        <svg {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M4 20h4.586a1 1 0 00.707-.293l9.414-9.414a2 2 0 000-2.828l-1.172-1.172a2 2 0 00-2.828 0L5.293 14.707A1 1 0 005 15.414V20z"/>
        </svg>
        @break
    @case('coffee')
        <svg {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 8h1a3 3 0 010 6h-1m0-6H5v7a4 4 0 004 4h4a4 4 0 004-4V8zM7 4v2m4-2v2m4-2v2"/>
        </svg>
        @break
    @case('cookie')
        <svg {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3a9 9 0 109 9 3 3 0 01-3-3 3 3 0 01-3-3 3 3 0 01-3-3z"/>
        </svg>
        @break
    @default
        <svg {{ $attributes->merge(['class' => $class]) }} fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
        </svg>
@endswitch