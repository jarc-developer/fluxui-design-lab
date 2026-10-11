<?php

namespace Tests\Feature;

use App\Models\Theme;
use App\Support\Theme\Legacy\LegacyThemeCompiler;
use App\Support\Theme\Legacy\LegacyThemeTokens;
use App\Support\Theme\ThemeCompiler;
use App\Support\Theme\ThemeTokens;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ThemeMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_migration_upcasts_legacy_rows_without_visual_drift(): void
    {
        $legacyPayload = array_replace(LegacyThemeTokens::defaults(), [
            'accent' => ['light' => '#059669', 'dark' => '#34d399'],
            'accentForeground' => ['light' => null, 'dark' => null],
            'base' => ['hue' => 165, 'chroma' => 0.008],
            'radius' => ['selector' => 0.25, 'field' => 1, 'box' => 1],
        ]);

        $id = DB::table('themes')->insertGetId([
            'name' => 'Legacy Emerald',
            'slug' => 'legacy-emerald',
            'tokens' => json_encode($legacyPayload),
            'is_active' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $legacyVars = (new LegacyThemeCompiler)->variables(LegacyThemeTokens::fromArray($legacyPayload));

        $migration = include database_path('migrations/2026_10_11_000000_migrate_theme_tokens_to_v2.php');
        $migration->up();

        $raw = json_decode((string) DB::table('themes')->where('id', $id)->value('tokens'), true);
        $this->assertFalse(ThemeTokens::isLegacy($raw));

        $theme = Theme::query()->findOrFail($id);
        $vars = (new ThemeCompiler)->variables($theme->tokens);

        $this->assertSame([], array_diff_assoc($legacyVars['light'], $vars['light']));
        $this->assertSame($legacyVars['dark'], array_intersect_key($vars['dark'], $legacyVars['dark']));
    }

    public function test_migration_is_idempotent_and_skips_v2_rows(): void
    {
        $theme = Theme::factory()->create();
        $before = DB::table('themes')->where('id', $theme->id)->value('tokens');

        $migration = include database_path('migrations/2026_10_11_000000_migrate_theme_tokens_to_v2.php');
        $migration->up();

        $this->assertSame($before, DB::table('themes')->where('id', $theme->id)->value('tokens'));
    }
}
