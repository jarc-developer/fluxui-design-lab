<?php

use App\Support\Theme\ThemeTokens;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Compile-snapshot legacy v1 (parametric) theme tokens into the v2
     * declarative shape. Migrated themes render identically, by construction:
     * the snapshot is exactly what the v1 compiler emitted (design.md D3).
     */
    public function up(): void
    {
        foreach (DB::table('themes')->get(['id', 'tokens']) as $row) {
            $data = json_decode((string) $row->tokens, true);

            if (! is_array($data) || ! ThemeTokens::isLegacy($data)) {
                continue;
            }

            $tokens = ThemeTokens::fromArray($data);

            Log::info('Migrated theme tokens to v2.', [
                'theme_id' => $row->id,
                'before' => $data,
                'after' => $tokens->toArray(),
            ]);

            DB::table('themes')->where('id', $row->id)->update([
                'tokens' => json_encode($tokens->toArray(), JSON_THROW_ON_ERROR | JSON_PRESERVE_ZERO_FRACTION),
            ]);
        }
    }

    public function down(): void
    {
        // v1 parameters cannot be reconstructed from a v2 value snapshot.
        // Roll back with git revert plus a database restore, or from the
        // before/after JSON logged by up().
    }
};
