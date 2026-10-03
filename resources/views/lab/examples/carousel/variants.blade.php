<div class="flex flex-col gap-6">
    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">fade</flux:text>

        <flux:carousel fade class="max-w-lg">
            <flux:carousel.slide>
                <div class="grid aspect-[3/2] w-full place-items-center rounded-xl bg-zinc-800 text-white">
                    <flux:text>Fade transition</flux:text>
                </div>
            </flux:carousel.slide>

            <flux:carousel.slide>
                <div class="grid aspect-[3/2] w-full place-items-center rounded-xl bg-zinc-700 text-white">
                    <flux:text>Second panel</flux:text>
                </div>
            </flux:carousel.slide>
        </flux:carousel>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">autoplay with a custom interval, wrap=&quot;rewind&quot;, advance=&quot;page&quot;</flux:text>

        <flux:carousel autoplay autoplay:interval="2500" wrap="rewind" advance="page" indicators class="max-w-lg">
            <flux:carousel.slide>
                <div class="grid aspect-[3/2] w-full place-items-center rounded-xl bg-indigo-100 text-indigo-900">Page one</div>
            </flux:carousel.slide>

            <flux:carousel.slide>
                <div class="grid aspect-[3/2] w-full place-items-center rounded-xl bg-emerald-100 text-emerald-900">Page two</div>
            </flux:carousel.slide>

            <flux:carousel.slide>
                <div class="grid aspect-[3/2] w-full place-items-center rounded-xl bg-amber-100 text-amber-900">Page three</div>
            </flux:carousel.slide>
        </flux:carousel>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">disabled</flux:text>

        <flux:carousel disabled class="max-w-lg">
            <flux:carousel.slide>
                <div class="grid aspect-[3/2] w-full place-items-center rounded-xl bg-zinc-100 text-zinc-500">Arrows are inert</div>
            </flux:carousel.slide>

            <flux:carousel.slide>
                <div class="grid aspect-[3/2] w-full place-items-center rounded-xl bg-zinc-100 text-zinc-500">Second slide</div>
            </flux:carousel.slide>
        </flux:carousel>
    </div>
</div>
