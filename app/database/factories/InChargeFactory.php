<?php

namespace Database\Factories;

use App\Models\InCharge;
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
            'classwork_id' => 1,
            'teacher_id' => 2,
        ];
    }
}
