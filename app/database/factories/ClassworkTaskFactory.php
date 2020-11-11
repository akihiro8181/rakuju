<?php

namespace Database\Factories;

use App\Models\ClassworkTask;
use App\Models\InCharge;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassworkTaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClassworkTask::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // 必要なデータ：名前（name）、テキスト(json.text)、リンク(json.text, json.url)
        // $json_data = [
        //     [
        //         'text' => 'test topic',
        //         'sort_num' => 0,
        //     ], 
        //     [
        //         'text' => 'test text',
        //         'sort_num' => 1,
        //     ], 
        // ];

        // return [
        //     'in_charge_id' => InCharge::inRandomOrder()->first()->id,
        //     'name' => $this->faker->words,
        //     'sort_num' => $this->faker->unique()->randomDigitNotNull,
        //     'content' => $json_data,
        // ];
    }
}
