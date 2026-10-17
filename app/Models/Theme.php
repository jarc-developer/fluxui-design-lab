<?php

namespace App\Models;

use App\Casts\ThemeTokensCast;
use App\Observers\ThemeObserver;
use App\Support\Theme\ThemeTokens;
use Database\Factories\ThemeFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * A saved theme. Exactly one row may be active; its tokens are applied to every page.
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property ThemeTokens $tokens
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[ObservedBy(ThemeObserver::class)]
class Theme extends Model
{
    /** @use HasFactory<ThemeFactory> */
    use HasFactory;

    protected $fillable = ['name', 'slug', 'tokens'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tokens' => ThemeTokensCast::class,
            'is_active' => 'boolean',
        ];
    }

    /**
     * @param  Builder<Theme>  $query
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * Make this the app-wide theme, deactivating every other one in the same transaction.
     */
    public function activate(): void
    {
        DB::transaction(function (): void {
            static::query()->whereKeyNot($this->getKey())->where('is_active', true)->update(['is_active' => false]);

            $this->is_active = true;
            $this->save();
        });

        // The bulk update above skips model events, so flush explicitly.
        ThemeObserver::flush();
    }

    /**
     * A slug unique across the table, derived from the name.
     */
    public static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'theme';
        $slug = $base;

        for ($i = 2; static::query()->where('slug', $slug)->when($ignoreId, fn (Builder $q) => $q->whereKeyNot($ignoreId))->exists(); $i++) {
            $slug = "{$base}-{$i}";
        }

        return $slug;
    }
}
