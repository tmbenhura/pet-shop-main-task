<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Payment;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Payment factory generates required fields.
     */
    public function test_factory_generates_required_fields(): void
    {
        $payment = Payment::factory()->create();

        $this->assertNotEmpty($payment->type);
        $this->assertNotEmpty($payment->details);
    }

    /**
     * Payment types are unique.
     */
    public function test_payment_types_are_unique(): void
    {
        try {
            Payment::factory()->create(['type' => 'credit_card']);
            Payment::factory()->create(['type' => 'credit_card']);
        } catch (Exception $e) {
        }

        $this->assertDatabaseCount('payments', 1);
    }
}
