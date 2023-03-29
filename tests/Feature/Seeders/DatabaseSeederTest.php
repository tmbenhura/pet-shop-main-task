<?php

namespace Tests\Feature\Seeders;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Payment;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Database can be seeded.
     */
    public function test_database_can_be_seeded(): void
    {
        $this->seed(DatabaseSeeder::class);

        $adminUsers = User::where('is_admin', true)->count();
        $nonAdminUsers = User::where('is_admin', false)->count();
        $orderStatuses = OrderStatus::count();
        $payments = Payment::count();
        $orders = Order::count();

        $this->assertEquals(1, $adminUsers);
        $this->assertEquals(10, $nonAdminUsers);
        $this->assertEquals(5, $orderStatuses);
        $this->assertEquals(3, $payments);
        $this->assertGreaterThanOrEqual(50, $orders);
    }
}
