<?php

use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Forms & Inputs')] class extends Component {
    public string $text = '';
    public string $email = '';
    public string $amount = '2500';
    public string $description = '';

    public bool $agree = true;
    public bool $notifications = true;
    public string $plan = 'pro';
    public string $released = '2026-01-15';
    public string $meeting = '14:30';
    public string $accent = '#6366f1';
    public int $volume = 60;
    public array $tags = ['livewire', 'flux'];
    public array $skus = ['flux-acme-001'];
    public $uploads = [];
}; ?>

    <div class="flex w-full flex-1 flex-col gap-8">
        <x-lab.section heading="Text inputs"
                      subheading="Icons, descriptions, trailing slots and validation states.">
            <div class="grid gap-4 md:grid-cols-2">
                <flux:input wire:model="text" label="Project name" placeholder="design-lab" />
                <flux:input wire:model="email" label="Email" type="email" icon="envelope"
                            placeholder="you@example.com" />
                <flux:input wire:model="amount" label="Amount" type="number" icon="currency-dollar" />
                <flux:input label="Disabled" value="Read only" disabled />
                <flux:input label="Invalid" value="nope" invalid />
                <flux:input label="With badge" badge="Pro" placeholder="Badge sits inline" />
            </div>

            <flux:textarea wire:model="description" label="Description"
                           placeholder="What are you building?" rows="3" />
        </x-lab.section>

        <x-lab.section heading="Selection"
                      subheading="Checkbox, radio, switch and toggle controls.">
            <div class="grid gap-4 md:grid-cols-2">
                <flux:checkbox wire:model="agree" label="I agree to the design guidelines" />
                <flux:checkbox label="Disabled option" disabled />

                <div>
                    <flux:label>Plan</flux:label>
                    <flux:radio.group wire:model="plan" label="Choose a plan">
                        <flux:radio value="free" label="Free" />
                        <flux:radio value="pro" label="Pro" />
                        <flux:radio value="team" label="Team" />
                    </flux:radio.group>
                </div>

                <div class="flex flex-col gap-4">
                    <flux:switch wire:model="notifications" label="Email notifications" />
                    <flux:toggle wire:model="notifications" label="Toggle variant" />
                </div>
            </div>

            <div class="max-w-xs">
                <flux:label>One-time passcode</flux:label>
                <flux:otp length="6" />
            </div>
        </x-lab.section>

        <x-lab.section heading="Pro pickers"
                      subheading="date-picker, time-picker, slider and color-picker ship with Flux Pro.">
            <div class="grid gap-4 md:grid-cols-2">
                <flux:date-picker wire:model="released" label="Release date" with-presets />
                <flux:time-picker wire:model="meeting" label="Standup time" />
                <div class="flex flex-col gap-2">
                    <flux:label>Volume</flux:label>
                    <flux:slider wire:model="volume" />
                    <flux:text size="sm" class="tabular-nums">{{ $volume }}%</flux:text>
                </div>
                <flux:color-picker wire:model="accent" label="Accent" swatches />
            </div>
        </x-lab.section>

        <x-lab.section heading="Pro tags & search"
                      subheading="pillbox for multi-value entry, autocomplete for filtered lookup.">
            <div class="grid gap-4 md:grid-cols-2">
                <flux:pillbox wire:model="tags" label="Tags" />
                <flux:autocomplete wire:model="skus" label="Component SKUs" />
            </div>
        </x-lab.section>

        <x-lab.section heading="File upload"
                      subheading="Drag-and-drop with an explicit browse button.">
            <flux:file-upload wire:model="uploads" multiple />
        </x-lab.section>
    </div>
