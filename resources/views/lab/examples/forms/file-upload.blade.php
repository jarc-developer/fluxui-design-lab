<div class="flex flex-col gap-6">
    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">
            Default dropzone with a custom heading and text
        </flux:text>

        <flux:file-upload wire:model="uploads" multiple>
            <flux:file-upload.dropzone heading="Drop the build artefacts here"
                                      text="ZIP, TAR or a single file up to 20 MB." />
        </flux:file-upload>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">
            with-progress and a different icon
        </flux:text>

        <flux:file-upload wire:model="uploads">
            <flux:file-upload.dropzone icon="archive-box-arrow-down" with-progress />
        </flux:file-upload>
    </div>

    <div>
        <flux:text size="xs" class="mb-1 font-medium text-zinc-500">
            An inline dropzone beside a summary
        </flux:text>

        <div class="flex items-start gap-3">
            <flux:file-upload wire:model="uploads" class="w-auto">
                <flux:file-upload.dropzone inline heading="Avatar" size="sm" />
            </flux:file-upload>

            <div class="flex flex-col gap-2">
                <flux:file-item heading="flux-ui-pro.zip" text="1.4 MB" icon="archive-box" />
                <flux:file-item heading="broken.pdf" text="Upload failed" invalid />
                <flux:file-item heading="diagram.png" text="PNG · 240 KB" icon="photo" image="/favicon.ico" />
            </div>
        </div>
    </div>
</div>
