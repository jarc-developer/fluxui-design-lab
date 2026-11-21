{{-- The active theme from the generator (/theme/generator). Printed after the Vite
     stylesheet, unlayered, so these variables win over app.css's @theme values on
     every page. The CSS is built only from validated tokens, see ThemeTokens. --}}
@php($activeTheme = app(\App\Support\Theme\ActiveTheme::class))

<meta name="lab-theme-font" content="{{ $activeTheme->fontUrl() ?? '' }}">

@if ($activeTheme->fontUrl())
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="stylesheet" href="{{ $activeTheme->fontUrl() }}" data-lab-theme-font>
@endif

@if ($activeTheme->css() !== '')
    <style id="lab-theme">{!! $activeTheme->css() !!}</style>
@endif
