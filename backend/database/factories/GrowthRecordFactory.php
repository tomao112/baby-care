<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GrowthRecord>
 */
class GrowthRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'child_id' => 6,  // 特定の子供ID
            'record_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'height' => $this->faker->randomFloat(1, 70, 120),
            'weight' => $this->faker->randomFloat(2, 8, 25),
            'memo' => $this->faker->optional(0.7)->sentence(),
        ];
    }
}
