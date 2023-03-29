<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\OrderStatus;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        collect(
            [
                'open',
                'pending payment',
                'paid',
                'shipped',
                'cancelled',
            ]
        )->each(
            fn (string $title): OrderStatus => OrderStatus::factory()->create(['title' => $title])
        );
    }
}
