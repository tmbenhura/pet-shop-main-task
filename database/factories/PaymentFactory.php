<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array{
     *     type: string,
     *     details: array<int, string>
     * }
     */
    public function definition(): array
    {
        return [
            'type' => fake()->text(50),
            'details' => fake()->randomElement(
                [
                    json_encode(
                        [
                            "holder_name" => "string",
                            "number" => "string",
                            "ccv" => "int",
                            "expire_date" => "string"
                        ]
                    ),
                    json_encode(
                        [
                            "first_name" => "string",
                            "last_name" => "string",
                            "address" => "string"
                        ]
                    ),
                    json_encode(
                        [
                            "swift" => "string",
                            "iban" => "string",
                            "name" => "string"
                        ]
                    )
                ]
            ),
        ];
    }
}
