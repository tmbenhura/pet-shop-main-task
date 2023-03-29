<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        collect(
            [
                'credit card',
                'cash on delivery',
                'bank transfer',
            ]
        )->each(
            fn (string $type): Payment => Payment::factory()->create(['type' => $type])
        );
    }
}
