<?php

namespace Database\Seeders;

use App\Models\Theme;
use App\Support\Theme\Presets;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    /**
     * Seed every preset as an editable theme and activate "Flux default", which
     * reproduces the look the app ships with. Re-running keeps existing rows and
     * leaves whichever theme is currently active alone.
     */
    public function run(): void
    {
        foreach (Presets::all() as $slug => $preset) {
            Theme::query()->firstOrCreate(
                ['slug' => $slug],
                ['name' => $preset['name'], 'tokens' => $preset['tokens']],
            );
        }

        if (! Theme::query()->active()->exists()) {
            Theme::query()->where('slug', 'flux-default')->firstOrFail()->activate();
        }
    }
}
