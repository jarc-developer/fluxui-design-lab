<div class="flex flex-col gap-6">
    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">fade with bleed (edge gradient mask on overflowed slides)</flux:text>

        <flux:carousel fade bleed class="max-w-xl">
            <flux:carousel.slide class="w-4/5 sm:w-1/2">
                <div class="grid aspect-[3/2] w-full place-items-center rounded-xl bg-zinc-800 text-white font-medium">Panel one</div>
            </flux:carousel.slide>

            <flux:carousel.slide class="w-4/5 sm:w-1/2">
                <div class="grid aspect-[3/2] w-full place-items-center rounded-xl bg-zinc-700 text-white font-medium">Panel two</div>
            </flux:carousel.slide>

            <flux:carousel.slide class="w-4/5 sm:w-1/2">
                <div class="grid aspect-[3/2] w-full place-items-center rounded-xl bg-zinc-600 text-white font-medium">Panel three</div>
            </flux:carousel.slide>

            <flux:carousel.slide class="w-4/5 sm:w-1/2">
                <div class="grid aspect-[3/2] w-full place-items-center rounded-xl bg-zinc-500 text-white font-medium">Panel four</div>
            </flux:carousel.slide>
        </flux:carousel>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">autoplay with custom interval, wrap=&quot;rewind&quot;, advance=&quot;page&quot; (moves visible page)</flux:text>

        <flux:carousel autoplay autoplay:interval="3000" wrap="rewind" advance="page" indicators class="max-w-xl">
            <flux:carousel.slide class="w-1/2">
                <div class="grid aspect-[3/2] w-full place-items-center rounded-xl bg-indigo-100 font-semibold text-indigo-900">Page 1A</div>
            </flux:carousel.slide>

            <flux:carousel.slide class="w-1/2">
                <div class="grid aspect-[3/2] w-full place-items-center rounded-xl bg-indigo-200 font-semibold text-indigo-900">Page 1B</div>
            </flux:carousel.slide>

            <flux:carousel.slide class="w-1/2">
                <div class="grid aspect-[3/2] w-full place-items-center rounded-xl bg-emerald-100 font-semibold text-emerald-900">Page 2A</div>
            </flux:carousel.slide>

            <flux:carousel.slide class="w-1/2">
                <div class="grid aspect-[3/2] w-full place-items-center rounded-xl bg-emerald-200 font-semibold text-emerald-900">Page 2B</div>
            </flux:carousel.slide>
        </flux:carousel>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">arrows:position=&quot;overlap&quot; and snap=&quot;mandatory&quot;</flux:text>

        <flux:carousel arrows:position="overlap" snap="mandatory" class="max-w-lg">
            <flux:carousel.slide class="w-full">
                <div class="grid aspect-[3/2] w-full place-items-center rounded-xl bg-violet-100 font-semibold text-violet-900">Overlap arrows: Card 1</div>
            </flux:carousel.slide>

            <flux:carousel.slide class="w-full">
                <div class="grid aspect-[3/2] w-full place-items-center rounded-xl bg-violet-200 font-semibold text-violet-900">Overlap arrows: Card 2</div>
            </flux:carousel.slide>
        </flux:carousel>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">disabled</flux:text>

        <flux:carousel disabled class="max-w-lg">
            <flux:carousel.slide class="w-full">
                <div class="grid aspect-[3/2] w-full place-items-center rounded-xl bg-zinc-100 text-zinc-500">Arrows are inert</div>
            </flux:carousel.slide>

            <flux:carousel.slide class="w-full">
                <div class="grid aspect-[3/2] w-full place-items-center rounded-xl bg-zinc-100 text-zinc-500">Second slide</div>
            </flux:carousel.slide>
        </flux:carousel>
    </div>
</div>
