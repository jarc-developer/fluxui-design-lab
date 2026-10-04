@php
    $palette = [
        'blue', 'violet', 'emerald', 'amber', 'rose', 'cyan', 'fuchsia', 'lime',
        'orange', 'teal', 'indigo', 'pink', 'sky', 'green', 'yellow', 'red', 'purple',
    ];
@endphp

<div class="flex flex-col gap-6">
    <div class="flex flex-col gap-2">
        <flux:text size="sm" class="font-medium">A pie is a chart of its own</flux:text>

        <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400">
            A pie cannot share a chart with a bar, line, area, point, axis, cursor or
            zero line — the chart JS logs a warning to the console and draws nothing at
            all. Only one pie is allowed per chart. That is why the pie presets on this
            page carry no axes, and why they are worth keeping in their own file.
        </flux:text>
    </div>

    <div class="flex flex-col gap-2">
        <flux:text size="sm" class="font-medium">Pie colours come from a fixed palette</flux:text>

        <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400">
            The slices are coloured by the engine, not by your markup, from
            <code class="font-mono text-xs">--color-&lt;name&gt;-500</code> and nothing else. A datum
            may carry its own <code class="font-mono text-xs">color</code> key, but a name outside
            this list falls back to the default palette rather than erroring.
        </flux:text>

        <div class="flex flex-wrap gap-2 pt-1">
            @foreach ($palette as $color)
                <div class="flex items-center gap-1.5">
                    <div class="size-4 rounded-full bg-{{ $color }}-500"></div>
                    <span class="font-mono text-xs text-zinc-500 dark:text-zinc-400">{{ $color }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <flux:separator />

    <div class="flex flex-col gap-2">
        <flux:text size="sm" class="font-medium">Three props are accepted and then ignored</flux:text>

        <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400">
            Each of these renders into the markup, so a snippet that sets one looks
            correct, and then nothing on the page changes. Worth knowing before you
            reach for them.
        </flux:text>

        <div class="flex flex-col gap-3 pt-1">
            <div>
                <code class="font-mono text-xs">tick-step</code> on an axis — the step on a linear
                axis comes from <code class="font-mono text-xs">interval</code> instead. The value is
                read into the chart config and never looked at again.
            </div>

            <div>
                <code class="font-mono text-xs">target-tick-count</code> on
                <code class="font-mono text-xs">flux:chart.axis.tick</code> — the attributes land on
                the inner <code class="font-mono text-xs">&lt;text&gt;</code>, but the engine reads the
                attribute off the <code class="font-mono text-xs">&lt;template name="tick-label"&gt;</code>
                instead. Use <code class="font-mono text-xs">tick-count</code> on the axis.
            </div>

            <div>
                <code class="font-mono text-xs">field</code> and
                <code class="font-mono text-xs">format</code> on
                <code class="font-mono text-xs">flux:chart.legend</code> — a legend is only its slot.
                Pass a <code class="font-mono text-xs">label</code>, or compose the row yourself as the
                grouped bars example does.
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-2">
        <flux:text size="sm" class="font-medium">And one that breaks the chart outright</flux:text>

        <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400">
            <code class="font-mono text-xs">scale</code> on an axis does not pick a scale in a family —
            it <em>is</em> the axis type, and the engine only implements
            <code class="font-mono text-xs">linear</code>, <code class="font-mono text-xs">time</code> and
            <code class="font-mono text-xs">categorical</code>. A time axis is already inferred from
            date-like index values. Setting anything else, <code class="font-mono text-xs">log</code>
            included, throws on the first draw and the chart renders nothing.
        </flux:text>
    </div>
</div>
