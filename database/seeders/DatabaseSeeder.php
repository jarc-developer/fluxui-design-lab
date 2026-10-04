<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(ThemeSeeder::class);

        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@design-lab.test',
        ]);

        $admin->assignRole('admin');

        User::factory()->create([
            'name' => 'Viewer',
            'email' => 'viewer@design-lab.test',
        ])->assignRole('viewer');
    }
}
