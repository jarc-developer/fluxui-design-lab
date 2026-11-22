@php
    $appName = config('app.name');
    $brandName = in_array(strtolower((string) $appName), ['', 'laravel', 'design-lab', 'design lab'], true) ? 'Flux Themes' : $appName;
@endphp
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="Design, preview, import, and export custom OKLCH themes for Flux UI and Tailwind CSS v4." />

<title>
    {{ filled($title ?? null) ? ($title === $brandName ? $brandName : $title.' — '.$brandName) : $brandName }}
</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

@fonts

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
