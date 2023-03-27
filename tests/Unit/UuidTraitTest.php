<?php

namespace Tests\Unit;

use App\Models\User;
use App\Traits\HasUuid;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
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

    /**
     *  Uuid models generate uuid.
     *
     * @dataProvider modelsWithUuid
     */
    public function test_uuid_models_generate_uuid($modelClass): void
    {
        $model = $modelClass::factory()->create();

        $this->assertNotEmpty($model->uuid);
        $this->assertTrue(Str::isUuid($model->uuid));
    }
}
