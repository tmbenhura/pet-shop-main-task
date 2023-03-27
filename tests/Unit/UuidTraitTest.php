<?php

namespace Tests\Unit;

use App\Models\User;
use App\Traits\HasUuid;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UuidTraitTest extends TestCase
{
    use RefreshDatabase;

    public function modelsWithUuid(): array
    {
        return [
            [User::class],
        ];
    }

    /**
     * Uuid models use Uuid trait.
     *
     * @dataProvider modelsWithUuid
     */
    public function test_uuid_models_use_uuid_trait($modelClass): void
    {
        $model = $modelClass::factory()->create();

        $this->assertTrue(in_array(HasUuid::class, class_uses_recursive($model::class)));
    }
}
