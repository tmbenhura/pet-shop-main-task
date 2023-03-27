<?php

namespace Tests\Feature\Seeders;

use App\Models\User;
use Database\Seeders\OrderSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class OrderSeederTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Order users can be seeded.
     */
    public function test_order_users_can_be_seeded(): void
    {
        $this->seed(OrderSeeder::class);

        $user = User::first();
        $distinctEmails = User::distinct('email')->count();
        $distinctPasswords = User::distinct('password')->count();

        $this->assertDatabaseCount('users', 10);
        $this->assertDatabaseMissing(
            'users',
            [
                'is_admin' => true,
            ]
        );
        $this->assertEquals(10, $distinctEmails);
        $this->assertEquals(1, $distinctPasswords);
        $this->assertTrue(Hash::check('userpassword', $user->password));
    }
}
