<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The root route serves the Flux Themes landing page and interactive theme generator.
     */
    public function test_root_returns_a_successful_response(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Flux Themes');
        $response->assertSee('Theme Generator');
    }
}
