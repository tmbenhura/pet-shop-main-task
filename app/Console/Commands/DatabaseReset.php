<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DatabaseReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset database to fresh start';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Clearing tables');
        collect(
            [
                'orders',
                'payments',
                'order_statuses',
                'users',
            ]
        )->each(
            fn (string $table) => DB::table($table)->delete()
        );

        $this->info('Re-seeding tables');
        $this->call('db:seed');
    }
}
