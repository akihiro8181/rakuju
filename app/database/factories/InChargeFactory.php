<?php

namespace Database\Factories;

use App\Models\InCharge;
use App\Models\Classwork;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InChargeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InCharge::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'classwork_id' => Classwork::inRandomOrder()->first()->id,
            'teacher_id' => User::inRandomOrder()->first()->id,
        ];
    }
}
