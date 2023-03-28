<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OrderSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory()->times(10)->create(
            [
                'is_admin' => false,
                'password' => Hash::make('userpassword'),
            ]
        );

        $ordersLeftToCreate = 50;
        do {
            $users->each(
                function (User $user) use (&$ordersLeftToCreate): void {
                    $times = rand(1, 5);
                    Order::factory()->times($times)->create(['user_id' => $user->id]);
                    $ordersLeftToCreate -= $times;
                }
            );
        } while (!!$ordersLeftToCreate);
    }
}
