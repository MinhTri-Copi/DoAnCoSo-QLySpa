{{-- Welcome Banner Header Component --}}
<div class="welcome-banner animate__animated animate__fadeIn">
    <h1><i class="{{ $icon ?? 'fas fa-spa' }}"></i> {{ $title ?? 'Page Title' }}</h1>
    <p>{{ $subtitle ?? 'Page description goes here' }}</p>
    <div class="shine-line"></div>
    @if(isset($actions))
    <div class="header-actions position-absolute" style="top: 30px; right: 35px;">
        <div class="d-flex gap-2">
            {!! $actions !!}
        </div>
    </div>
    @endif
</div>

{{-- Add the welcome-banner styles to your layouts/app.blade.php --}}
{{-- Or include this in your specific pages that need it --}} 