<div class="grid gap-4 md:grid-cols-2">
    <div class="flex flex-col gap-2">
        <flux:label>Six digits</flux:label>
        <flux:otp length="6" />
    </div>

    <div class="flex flex-col gap-2">
        <flux:label>Four digits</flux:label>
        <flux:otp length="4" />
    </div>

    <div class="flex flex-col gap-2">
        <flux:label>private — never sent over the wire</flux:label>
        <flux:otp length="6" private />
    </div>

    <div class="flex flex-col gap-2">
        <flux:label>Default length</flux:label>
        <flux:otp />
    </div>
</div>
