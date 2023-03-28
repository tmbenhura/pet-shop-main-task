<?php

namespace Tests\Feature\Seeders;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserSeederTest extends TestCase
{
    use RefreshDatabase;

    /**
     * An admin user can be seeded.
     */
    public function test_an_admin_user_can_be_seeded(): void
    {
        $this->seed(AdminUserSeeder::class);

        $admin = User::first();

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas(
            'users',
            [
                'email' => 'admin@buckhill.co.uk',
                'is_admin' => true,
            ]
        );
        $this->assertTrue(Hash::check('admin', $admin->password));
    }
}
