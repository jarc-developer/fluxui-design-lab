<?php

namespace Database\Factories;

use App\Models\Theme;
use App\Support\Theme\ThemeTokens;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Theme>
 */
class ThemeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $words = (array) $this->faker->unique()->words(2);
        $name = Str::title(implode(' ', $words));

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'tokens' => ThemeTokens::defaults(),
        ];
    }

    /**
     * Mark the theme active. Use ->create() then ->activate() when other active themes exist.
     */
    public function active(): static
    {
        return $this->afterCreating(fn (Theme $theme) => $theme->activate());
    }
}
