<?php

namespace Database\Factories;

use App\Models\Hakim;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hakim>
 */
class HakimFactory extends Factory
{
    protected $model = Hakim::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => $this->faker->name(),
            'nomor_whatsapp' => '08' . $this->faker->numerify('#########'),
            'email' => $this->faker->unique()->safeEmail(),
        ];
    }
}
