<?php

namespace App\Console\Commands;

use App\Support\Theme\Presets;
use App\Support\Theme\ThemeCodec;
use App\Support\Theme\ThemeCompiler;
use App\Support\Theme\ThemeTokens;
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
                            {theme=flux-default : Built-in preset slug (flux-default, indigo, emerald, rose, ocean, cupcake)}
                            {--code= : Optional compressed share code (?theme=...) to export instead of a preset}
                            {--name= : Custom theme name for the CSS header when exporting via --code}
                            {--path= : File path to write the output to instead of stdout}
                            {--json : Export raw JSON tokens instead of Tailwind CSS}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export a built-in preset or share code as Tailwind v4 CSS or JSON tokens';

    /**
     * Execute the console command.
     */
    public function handle(ThemeCompiler $compiler): int
    {
        $code = $this->option('code');

        if (is_string($code) && trim($code) !== '') {
            $tokens = ThemeCodec::decode(trim($code));
            if ($tokens === null) {
                $this->error('Invalid theme share code provided to --code.');

                return self::FAILURE;
            }

            $customName = $this->option('name');
            $name = is_string($customName) && trim($customName) !== '' ? trim($customName) : 'Shared Theme';
        } else {
            $slug = (string) $this->argument('theme');
            $preset = Presets::all()[$slug] ?? null;

            if ($preset === null) {
                $available = implode(', ', array_keys(Presets::all()));
                $this->error("Preset [{$slug}] not found. Available presets: {$available}");

                return self::FAILURE;
            }

            $tokens = ThemeTokens::fromArray($preset['tokens']);
            $name = $preset['name'];
        }

        $output = $this->option('json')
            ? (string) json_encode($tokens->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)
            : $compiler->export($tokens, $name);

        $path = $this->option('path');

        if (is_string($path) && $path !== '') {
            File::ensureDirectoryExists(dirname($path));
            File::put($path, $output);
            $this->info("Theme [{$name}] exported to [{$path}].");
        } else {
            $this->line($output);
        }

        return self::SUCCESS;
    }
}
