@if(isset($seo))
    <!-- Meta tags básicos -->
    @if($seo->meta_title)
        <title>{{ $seo->meta_title }}</title>
    @endif
    
    @if($seo->meta_description)
        <meta name="description" content="{{ $seo->meta_description }}">
    @endif
    
    @if($seo->meta_keywords)
        <meta name="keywords" content="{{ $seo->meta_keywords }}">
    @endif
    
    @if($seo->meta_robots)
        <meta name="robots" content="{{ $seo->meta_robots }}">
    @endif
    
    @if($seo->canonical_url)
        <link rel="canonical" href="{{ $seo->canonical_url }}">
    @endif
    
    <!-- Open Graph / Facebook -->
    @if($seo->og_title || $seo->meta_title)
        <meta property="og:title" content="{{ $seo->og_title ?? $seo->meta_title }}">
    @endif
    
    @if($seo->og_description || $seo->meta_description)
        <meta property="og:description" content="{{ $seo->og_description ?? $seo->meta_description }}">
    @endif
    
    @if($seo->og_type)
        <meta property="og:type" content="{{ $seo->og_type }}">
    @endif
    
    @if($seo->og_url || $seo->canonical_url)
        <meta property="og:url" content="{{ $seo->og_url ?? $seo->canonical_url ?? url()->current() }}">
    @endif
    
    @if($seo->og_site_name)
        <meta property="og:site_name" content="{{ $seo->og_site_name }}">
    @endif
    
    @if($seo->og_locale)
        <meta property="og:locale" content="{{ $seo->og_locale }}">
    @endif
    
    @if($seo->og_image)
        <meta property="og:image" content="{{ asset($seo->og_image) }}">
    @endif
    
    <!-- Twitter -->
    @if($seo->twitter_card)
        <meta name="twitter:card" content="{{ $seo->twitter_card }}">
    @endif
    
    @if($seo->twitter_title || $seo->og_title || $seo->meta_title)
        <meta name="twitter:title" content="{{ $seo->twitter_title ?? $seo->og_title ?? $seo->meta_title }}">
    @endif
    
    @if($seo->twitter_description || $seo->og_description || $seo->meta_description)
        <meta name="twitter:description" content="{{ $seo->twitter_description ?? $seo->og_description ?? $seo->meta_description }}">
    @endif
    
    @if($seo->twitter_site)
        <meta name="twitter:site" content="{{ $seo->twitter_site }}">
    @endif
    
    @if($seo->twitter_creator)
        <meta name="twitter:creator" content="{{ $seo->twitter_creator }}">
    @endif
    
    @if($seo->twitter_image || $seo->og_image)
        <meta name="twitter:image" content="{{ asset($seo->twitter_image ?? $seo->og_image) }}">
    @endif
    
    @if($seo->twitter_image_alt)
        <meta name="twitter:image:alt" content="{{ $seo->twitter_image_alt }}">
    @endif
@else
    <!-- Metadatos SEO por defecto si no hay configuración específica -->
    <title>{{ config('app.name') }}</title>
@endif