<div class="flex flex-col gap-4">
    <div class="flex items-center justify-between">
        <flux:heading size="lg">Project showcase</flux:heading>
        <flux:carousel.controls name="gallery" />
    </div>

    <flux:carousel name="gallery" bleed :arrows="false" class="max-w-2xl">
        <flux:carousel.slide class="w-4/5 sm:w-1/2 md:w-1/3">
            <div class="grid aspect-[3/2] w-full place-items-center rounded-xl bg-sky-100 font-semibold text-sky-900">One</div>
        </flux:carousel.slide>

        <flux:carousel.slide class="w-4/5 sm:w-1/2 md:w-1/3">
            <div class="grid aspect-[3/2] w-full place-items-center rounded-xl bg-fuchsia-100 font-semibold text-fuchsia-900">Two</div>
        </flux:carousel.slide>

        <flux:carousel.slide class="w-4/5 sm:w-1/2 md:w-1/3">
            <div class="grid aspect-[3/2] w-full place-items-center rounded-xl bg-lime-100 font-semibold text-lime-900">Three</div>
        </flux:carousel.slide>

        <flux:carousel.slide class="w-4/5 sm:w-1/2 md:w-1/3">
            <div class="grid aspect-[3/2] w-full place-items-center rounded-xl bg-amber-100 font-semibold text-amber-900">Four</div>
        </flux:carousel.slide>
    </flux:carousel>
</div>
