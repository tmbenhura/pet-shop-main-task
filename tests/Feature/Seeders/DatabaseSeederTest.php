<?php

namespace Tests\Feature\Seeders;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\OrderSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
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

        $this->assertEquals(1, $adminUsers);
        $this->assertEquals(10, $nonAdminUsers);
    }
}
