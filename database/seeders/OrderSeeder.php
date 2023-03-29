<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Order;
use App\Models\OrderStatus;
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

        if (!OrderStatus::exists()) {
            $this->call(OrderStatusSeeder::class);
        }

        $availableOrderStatuses = OrderStatus::get();

        $ordersLeftToCreate = 50;
        do {
            $users->each(
                function (User $user) use (&$ordersLeftToCreate, $availableOrderStatuses): void {
                    $orderStatus = $availableOrderStatuses->get(rand(0, $availableOrderStatuses->count() - 1));

                    $times = rand(1, 5);
                    Order::factory()->times($times)->create(
                        [
                            'user_id' => $user->id,
                            'order_status_uuid' => $orderStatus->uuid,
                        ]
                    );
                    $ordersLeftToCreate -= $times;
                }
            );
        } while (!!$ordersLeftToCreate);
    }
}
