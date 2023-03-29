<?php

declare(strict_types=1);

namespace Tests\Feature\Commands;

use Tests\TestCase;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

    /**
     * Reset command is scheduled to run a midnight.
     */
    public function test_reset_command_is_scheduled_to_run_at_midnight(): void
    {
        $schedule = app(Schedule::class);

        $event = collect($schedule->events())
            ->filter(
                function (Event $event) {
                    return stripos($event->command ?? '', 'db:reset');
                }
            )->first();

        if (!$event) {
            $this->fail('Schedule entry not found');
        }

        $this->assertEquals('UTC', $event->timezone);
        $this->assertEquals('0 0 * * *', $event->expression);
    }
}
