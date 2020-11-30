<?php

namespace Database\Factories;

use App\Models\Tutor;
use App\Models\User;
use App\Models\InCharge;
use Illuminate\Database\Eloquent\Factories\Factory;

class TutorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tutor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'in_charge_id' => InCharge::inRandomOrder()->first()->id,
        ];
    }
}
