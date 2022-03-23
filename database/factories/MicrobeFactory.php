<?php

namespace Database\Factories;

use App\Models\Microbe;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class MicrobeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Microbe::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $sub = SubCategory::inRandomOrder()->first();
        return [
            'title'             => $this->faker->word,
            'description'       => $this->faker->paragraph(1),
            'image'             => 'https://via.placeholder.com/720',
            'user_id'           => 1,
            'sub_category_id'   => $sub->id
        ];
    }
}
