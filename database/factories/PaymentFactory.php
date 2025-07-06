<?php

namespace Database\Factories;

use App\Enums\PaymentStatusEnum;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = PaymentStatusEnum::cases()[array_rand(PaymentStatusEnum::cases())];

        return [
            'payment_id' => Str::uuid(),
            'amount' => rand(1, 100000),
            'details' => fake()->creditCardDetails['number'],
            'status' => strtolower($status->name),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
