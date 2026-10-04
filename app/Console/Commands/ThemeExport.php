<?php

namespace App\Console\Commands;

use App\Models\Theme;
use App\Support\Theme\ThemeCompiler;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ThemeExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'theme:export
                            {theme? : The slug of the theme to export (defaults to currently active theme)}
                            {--path= : File path to write the output to instead of stdout}
                            {--json : Export raw JSON tokens instead of Tailwind CSS}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export a theme as Tailwind v4 CSS or JSON tokens for use in other projects';

    /**
     * Execute the console command.
     */
    public function handle(ThemeCompiler $compiler): int
    {
        $slug = $this->argument('theme');

        $theme = $slug
            ? Theme::query()->where('slug', $slug)->first()
            : Theme::query()->active()->first();

        if (! $theme) {
            $this->error($slug ? "Theme with slug [{$slug}] not found." : 'No active theme found.');

            return self::FAILURE;
        }

        $output = (string) ($this->option('json')
            ? json_encode($theme->tokens->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)
            : $compiler->export($theme->tokens, $theme->name));

        $path = $this->option('path');

        if ($path) {
            File::ensureDirectoryExists(dirname($path));
            File::put($path, $output);
            $this->info("Theme [{$theme->name}] exported to [{$path}].");
        } else {
            $this->line($output);
        }

        return self::SUCCESS;
    }
}
