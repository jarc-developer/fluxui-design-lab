<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The lab replaced the starter kit's public welcome page: the root route
     * now resolves to the sign-in screen for guests and the dashboard once
     * signed in.
     */
    public function test_root_returns_a_successful_response(): void
    {
        $response = $this->actingAs(User::factory()->create())->get(route('home'));

        $response->assertRedirect(route('dashboard'));
    }
}
