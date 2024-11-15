<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ParkingRecord;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentTransaction>
 */
class PaymentTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $parkingRecord = ParkingRecord::factory()->create();

        return [
            'payment_status' => $this->faker->randomElement(['1', '0']),
            'payment_method_id' => $this->faker->randomElement([1, 2]),
            'paid_datetime' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'fee' => $this->faker->randomFloat(20, 10, 100),
            'parking_record_id' => $parkingRecord->id,
        ];
    }
}
