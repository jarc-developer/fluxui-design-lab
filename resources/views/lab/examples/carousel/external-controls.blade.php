<div class="flex flex-col gap-3">
    <flux:carousel name="gallery" class="max-w-2xl" bleed>
        <flux:carousel.slide>
            <div class="grid aspect-[3/2] w-full place-items-center rounded-xl bg-sky-100 text-sky-900">One</div>
        </flux:carousel.slide>

        <flux:carousel.slide>
            <div class="grid aspect-[3/2] w-full place-items-center rounded-xl bg-fuchsia-100 text-fuchsia-900">Two</div>
        </flux:carousel.slide>

        <flux:carousel.slide>
            <div class="grid aspect-[3/2] w-full place-items-center rounded-xl bg-lime-100 text-lime-900">Three</div>
        </flux:carousel.slide>
    </flux:carousel>

    <flux:carousel.controls name="gallery" />
</div>
