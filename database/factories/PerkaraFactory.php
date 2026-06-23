<?php

namespace Database\Factories;

use App\Models\Perkara;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Perkara>
 */
class PerkaraFactory extends Factory
{
    protected $model = Perkara::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $num = $this->faker->unique()->numberBetween(1, 500);
        $year = $this->faker->numberBetween(2023, 2026);
        return [
            'nomor_perkara' => "{$num}/G/{$year}/PTUN.JKT",
            'tahun' => $year,
            'keterangan' => $this->faker->paragraph(),
        ];
    }
}
