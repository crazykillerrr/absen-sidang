<?php

namespace Database\Factories;

use App\Models\PaniteraPengganti;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaniteraPengganti>
 */
class PaniteraPenggantiFactory extends Factory
{
    protected $model = PaniteraPengganti::class;

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
