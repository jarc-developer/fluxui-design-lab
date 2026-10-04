@props([
    'src',
    'title' => null,
    'subheading' => null,
    'snippet' => true,
    'data' => [],
])

@php
    // Blade hands `snippet="false"` over as the string "false", which is truthy
    // in PHP, so the documented string form has to be normalised before it is
    // tested. filter_var accepts both that and a real boolean.
    $snippet = filter_var($snippet, FILTER_VALIDATE_BOOLEAN);

    $source = $snippet
        ? trim(Illuminate\Support\Facades\File::get(resource_path('views/'.str_replace('.', '/', $src).'.blade.php')))
        : null;
@endphp

<div data-lab-example {{ $attributes->class('flex flex-col gap-3') }}>
    @if ($title)
        <div class="flex flex-col gap-0.5">
            <flux:heading size="sm">{{ $title }}</flux:heading>

            @if ($subheading)
                <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400">{{ $subheading }}</flux:text>
            @endif
        </div>
    @endif

    <div class="flex flex-col gap-3">
        {{-- Anonymous components get their own scope, so anything a fragment
             needs has to be handed to it explicitly through the data prop. --}}
        @include($src, $data)

        @if ($snippet)
            <flux:card class="gap-0 overflow-hidden">
                <div class="flex items-center justify-between gap-2 border-b border-zinc-200 bg-zinc-50 px-3 py-1.5 dark:border-white/10 dark:bg-white/5">
                    <flux:text size="xs" class="font-mono text-zinc-500 dark:text-zinc-400">blade</flux:text>

                    <flux:button
                        size="xs"
                        variant="subtle"
                        icon="document-duplicate"
                        x-data
                        x-on:click="navigator.clipboard.writeText($el.closest('[data-lab-example]').querySelector('pre').innerText)"
                    >
                        Copy
                    </flux:button>
                </div>

                <pre class="overflow-x-auto p-3 text-xs leading-relaxed whitespace-pre text-zinc-700 dark:bg-zinc-900 dark:text-zinc-300"><code>{{ $source }}</code></pre>
            </flux:card>
        @endif
    </div>
</div>
