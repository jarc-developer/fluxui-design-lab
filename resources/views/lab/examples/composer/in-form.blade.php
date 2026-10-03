<form wire:submit="send" class="flex flex-col gap-3">
    <flux:composer wire:model="body" label="Reply" placeholder="Write a reply…" />

    <div class="flex justify-end">
        <flux:button type="submit" size="sm">Post reply</flux:button>
    </div>
</form>
