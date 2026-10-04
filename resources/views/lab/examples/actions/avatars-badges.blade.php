<div class="flex flex-col gap-6">
    <div class="flex flex-wrap items-center gap-2">
        <flux:avatar name="Ana Ruiz" />
        <flux:avatar name="Kim Ito" />
        <flux:avatar name="Sam Bell" />
        <flux:avatar name="Ada Nwosu" />
        <flux:avatar name="Jules Park" />
    </div>

    <div class="flex flex-wrap items-center gap-2">
        <flux:avatar.group>
            <flux:avatar name="Ana Ruiz" />
            <flux:avatar name="Kim Ito" />
            <flux:avatar name="Sam Bell" />
            <flux:avatar name="Ada Nwosu" />
        </flux:avatar.group>

        <flux:text size="sm" class="text-zinc-500">Stacked group</flux:text>
    </div>

    <div class="flex flex-wrap items-center gap-2">
        <flux:badge>Dismissable</flux:badge>
        <flux:badge color="green">
            With a close button
            <flux:badge.close />
        </flux:badge>
        <flux:badge color="red" variant="solid">Deprecated</flux:badge>
        <flux:badge color="amber" size="sm">Beta</flux:badge>
    </div>

    <div class="flex flex-wrap items-center gap-2">
        <flux:profile name="Ana Ruiz" :initials="'AR'" />
        <flux:profile name="Kim Ito" :initials="'KI'" :chevron="false" />
    </div>
</div>
