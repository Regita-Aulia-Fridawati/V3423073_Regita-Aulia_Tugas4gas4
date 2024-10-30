<?php

namespace Database\Factories;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

class BarangFactory extends Factory
{
    protected $model = Barang::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'category' => 'Bread',
            'stock' => $this->faker->numberBetween(50, 200),
            'price' => $this->faker->numberBetween(1000, 20000),
            'note' => $this->faker->sentence,
            'image' => UploadedFile::fake()->image('default.jpg') // Setel default image
        ];
    }
}
