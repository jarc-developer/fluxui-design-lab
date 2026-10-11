<flux:card class="flex flex-col gap-4">
    <div class="flex items-center justify-between">
        <flux:heading size="base">Usage & Quotas</flux:heading>
        <flux:badge size="sm" color="green">Healthy</flux:badge>
    </div>

    <div class="space-y-4">
        <div class="space-y-1.5">
            <div class="flex items-center justify-between text-xs">
                <span class="font-medium text-zinc-600 dark:text-zinc-400">API Requests</span>
                <span class="font-mono text-zinc-900 dark:text-zinc-100">842k / 1M</span>
            </div>
            <div class="w-full bg-zinc-200 dark:bg-zinc-700 h-1.5 rounded-full overflow-hidden">
                <div class="bg-indigo-500 h-full rounded-full" style="width: 84%"></div>
            </div>
        </div>

        <div class="space-y-1.5">
            <div class="flex items-center justify-between text-xs">
                <span class="font-medium text-zinc-600 dark:text-zinc-400">Database Storage</span>
                <span class="font-mono text-zinc-900 dark:text-zinc-100">14.2 GB / 25 GB</span>
            </div>
            <div class="w-full bg-zinc-200 dark:bg-zinc-700 h-1.5 rounded-full overflow-hidden">
                <div class="bg-blue-500 h-full rounded-full" style="width: 57%"></div>
            </div>
        </div>

        <div class="space-y-1.5">
            <div class="flex items-center justify-between text-xs">
                <span class="font-medium text-zinc-600 dark:text-zinc-400">Compute Workers</span>
                <span class="font-mono text-zinc-900 dark:text-zinc-100">8 / 16 nodes</span>
            </div>
            <div class="w-full bg-zinc-200 dark:bg-zinc-700 h-1.5 rounded-full overflow-hidden">
                <div class="bg-emerald-500 h-full rounded-full" style="width: 50%"></div>
            </div>
        </div>
    </div>

    <flux:button variant="subtle" size="xs" class="w-full mt-1">Upgrade Tier</flux:button>
</flux:card>
