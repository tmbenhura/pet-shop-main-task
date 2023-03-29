<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResetDatabaseCommandTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Reset command clears and reseeds database.
     */
    public function test_rest_command_clears_and_reseeds_database(): void
    {
        $console = $this->artisan('db:reset');

        $console->expectsOutput("Clearing tables");
        $console->expectsOutput("Re-seeding tables");
        $console->assertExitCode(0);
    }
}
