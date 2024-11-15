<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ParkingRecord>
 */
class ParkingRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'parking_pass_code' => $this->faker->unique()->numberBetween(10001, 50000),
            'parking_pass_type' => '0',
            'license_plate' => $this->faker->unique()->regexify('[A-Z]{3}-[0-9]{4}'), 
            'license_plate_path' => $this->faker->imageUrl(640, 480, 'cars'),
            'stamp_id' => $this->faker->randomElement(['10']),
            'stamp_qty' => $this->faker->numberBetween(1, 6),
            'carin_datetime' => now()->format('Y-m-d H:i:s'),
            'carout_datetime' => now()->modify('+3 hours')->format('Y-m-d H:i:s'),
        ];
    }
}
