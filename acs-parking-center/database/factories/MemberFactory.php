<?php

// database/factories/MemberFactory.php
namespace Database\Factories;

use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberFactory extends Factory
{
    protected $model = \App\Models\Member::class;

    public function definition()
    {
        return [
            'member_code' => $this->faker->unique()->text(10),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'phone' => $this->faker->numerify('###########'), // 11 digits phone number
            'member_status' => $this->faker->randomElement(['active', 'inactive']),
            'member_type_id' => 1, // Use static value, update when you create MemberType model
            'place_id' => 1, // Use static value, update when you create Place model
            'id_card' => $this->faker->numerify('#############'), // 13 digits ID card
            'license_driver' => $this->faker->optional()->text(20),
            'license_plate' => $this->faker->optional()->text(20),
            'issue_date' => $this->faker->optional()->date,
            'expiry_date' => $this->faker->optional()->date,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
