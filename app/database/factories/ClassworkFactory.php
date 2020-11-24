<?php

namespace Database\Factories;

use App\Models\Classwork;
use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassworkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Classwork::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'school_id' => School::inRandomOrder()->first()->id,
        ];
    }
}
