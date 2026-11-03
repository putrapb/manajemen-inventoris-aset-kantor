<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Asset>
 */
class AssetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->words(3, true),
            'kode' => 'AST-'.fake()->unique()->numerify('####'),
            'category_id' => Category::factory(),
            'lokasi' => fake()->randomElement(['Gudang Utama', 'Ruang Meeting', 'Lantai 1', 'Lantai 2']),
            'status' => fake()->randomElement(Asset::STATUSES),
            'tanggal_beli' => fake()->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
            'nilai' => fake()->randomFloat(2, 0, 50000000),
        ];
    }
}
