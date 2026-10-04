<?php

namespace App\Casts;

use App\Support\Theme\ThemeTokens;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

/**
 * Stores ThemeTokens as JSON and hydrates them back, validated, on read.
 *
 * @implements CastsAttributes<ThemeTokens, ThemeTokens|array<string, mixed>>
 */
class ThemeTokensCast implements CastsAttributes
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ThemeTokens
    {
        $data = is_string($value) ? json_decode($value, true) : null;

        // A row edited by hand into something invalid falls back to the defaults
        // rather than taking every page down with it.
        return ThemeTokens::tryFrom(is_array($data) ? $data : [])
            ?? ThemeTokens::fromArray(ThemeTokens::defaults());
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        if (is_array($value)) {
            $value = ThemeTokens::fromArray($value);
        }

        if (! $value instanceof ThemeTokens) {
            throw new InvalidArgumentException('Theme tokens must be an array or a ThemeTokens instance.');
        }

        return json_encode($value->toArray(), JSON_THROW_ON_ERROR | JSON_PRESERVE_ZERO_FRACTION);
    }
}
