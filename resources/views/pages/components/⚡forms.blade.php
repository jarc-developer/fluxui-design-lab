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
    public array $priceRange = [20, 80];
    public array $dateRange = ['2026-01-15', '2026-01-22'];
    public array $tags = ['livewire', 'flux'];
    public array $skus = ['flux-acme-001'];
    public array $channels = ['email'];
    public array $stacks = ['php'];
    public array $licenses = ['mit'];
    public string $framework = 'livewire';
    public string $status = 'active';
    public string $region = 'eu-west';
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

        <x-lab.section heading="Input affixes"
                      subheading="Clearable, copyable, viewable, expandable and masked inputs, plus the file and loading states.">
            <x-lab.example src="lab.examples.forms.input-affixes"
                           title="Adornments and masks"
                           subheading="mask takes an IMask template, or mask:function for a computed one — there are no named masks." />

            <x-lab.example src="lab.examples.forms.input-group"
                           title="Input groups"
                           subheading="input.group welds several controls into one field, with input.group.prefix, .suffix and .affix as the edges." />
        </x-lab.section>

        <x-lab.section heading="Selection"
                      subheading="Checkbox, radio, switch and toggle controls.">
            <div class="grid gap-4 md:grid-cols-2">
                <flux:checkbox wire:model="agree" label="I agree to the design guidelines" />
                <flux:checkbox label="Disabled option" disabled />

                <flux:radio.group wire:model="plan" label="Choose a plan">
                    <flux:radio value="free" label="Free" />
                    <flux:radio value="pro" label="Pro" />
                    <flux:radio value="team" label="Team" />
                </flux:radio.group>

                <div class="flex flex-col gap-4">
                    <flux:switch wire:model="notifications" label="Email notifications" />
                    <flux:toggle wire:model="notifications" label="Toggle variant" />
                </div>
            </div>

            <div class="max-w-xs">
                <flux:otp length="6" label="One-time passcode" />
            </div>
        </x-lab.section>

        <x-lab.section heading="Choice groups"
                      subheading="Every checkbox.group and radio.group variant.">
            <x-lab.example src="lab.examples.forms.checkbox-groups"
                           title="Checkbox groups"
                           subheading="default, pills, buttons and cards. checkbox.all toggles every sibling." />

            <x-lab.example src="lab.examples.forms.radio-groups"
                           title="Radio groups"
                           subheading="default, segmented, pills, buttons and cards." />
        </x-lab.section>

        <x-lab.section heading="Selects"
                      subheading="The free native select plus the three Pro variants that ship with the lab.">
            <div class="grid gap-4 md:grid-cols-2">
                <flux:select wire:model="status" label="Native select">
                    <flux:select.option value="backlog">Backlog</flux:select.option>
                    <flux:select.option value="active">Active</flux:select.option>
                    <flux:select.option value="done">Done</flux:select.option>
                </flux:select>

                <flux:select wire:model="status" label="With a placeholder" placeholder="Choose…">
                    <flux:select.option value="backlog">Backlog</flux:select.option>
                    <flux:select.option value="active">Active</flux:select.option>
                </flux:select>
            </div>

            <x-lab.example src="lab.examples.forms.select-variants"
                           title="combobox and listbox"
                           subheading="flux:select still dispatches on variant; Flux Pro adds combobox and listbox, and both compose their own trigger and search." />

            <x-lab.example src="lab.examples.forms.select-custom"
                           title="Composed by hand"
                           subheading="variant=&quot;custom&quot; gives a bare ui-select: you place select.button or select.input, select.search, select.options and the groups yourself." />
        </x-lab.section>

        <x-lab.section heading="Pro pickers"
                      subheading="date-picker, time-picker, slider and color-picker ship with Flux Pro.">
            <x-lab.example :data="['dateRange' => $dateRange]" src="lab.examples.forms.date-picker"
                           title="Date picker"
                           subheading="Button and input triggers, confirmation, typed inputs, week numbers, an inline dropdown mode and custom presets." />

            <x-lab.example src="lab.examples.forms.time-picker"
                           title="Time picker"
                           subheading="Button and input triggers, blocked times and the inline dropdown mode." />

            <x-lab.example :data="['priceRange' => $priceRange]" src="lab.examples.forms.slider"
                           title="Slider"
                           subheading="Single and range values, labelled ticks, dot ticks with tick:position=&quot;inside&quot;, and the disabled state." />

            <x-lab.example src="lab.examples.forms.color-picker"
                           title="Color picker"
                           subheading="Every format, both trigger types, copyable, clearable, confirmation, the screen dropper and custom swatch sets — plus a hand-composed popover." />
        </x-lab.section>

        <x-lab.section heading="Multi-value input"
                      subheading="pillbox for chips, autocomplete for filtered lookup.">
            <x-lab.example src="lab.examples.forms.multi-value"
                           title="pillbox and autocomplete"
                           subheading="pillbox default and combobox variants with create/empty affordances; autocomplete with filter on and off." />
        </x-lab.section>

        <x-lab.section heading="One-time passcodes"
                      subheading="OTP with a configurable length, and the private variant.">
            <x-lab.example src="lab.examples.forms.otp"
                           title="OTP"
                           subheading="A private OTP is never sent to the server." />
        </x-lab.section>

        <x-lab.section heading="Field primitives"
                      subheading="The building blocks each form control is composed from.">
            <x-lab.example src="lab.examples.forms.fields"
                           title="fieldset, field, legend, description and error"
                           subheading="Most controls accept label, description and badge directly, but these are what sits underneath when you need full control." />
        </x-lab.section>

        <x-lab.section heading="File upload"
                      subheading="Drag-and-drop with an explicit browse button.">
            <flux:file-upload wire:model="uploads" multiple />

            <x-lab.example src="lab.examples.forms.file-upload"
                           title="Dropzone and file items"
                           subheading="The inline dropzone, its progress state, and the file-item rows that appear once a file lands." />
        </x-lab.section>
    </div>
