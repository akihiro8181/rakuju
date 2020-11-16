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
        // $c = new ClassworkTask
        // $c->in_charge_id = InCharge::inRandomOrder()->first()->id
        // $c->name = "Task01"
        // $c->deadline = now()
        // $c->sort_num = 0
        // $c->contents = [['type'=>'text','text'=>'test topic', 'sort_num'=>0],['type'=>'link','text'=>'test link','url'=>'http://localhost','sort_num'=>1],['type'=>'link','text'=>'test link','url'=>'http://localhost/dashboard','sort_num'=>2],['type'=>'text','text'=>'test topic', 'sort_num'=>3]]
        // $c->save()
        $json_data = [
            [
                'type' => 'text',
                'text' => 'test topic',
                'sort_num' => 0,
            ], 
            [
                'type' => 'link',
                'text' => 'test text',
                'url' => 'localhost',
                'sort_num' => 1,
            ],
        ];

        return [
            'in_charge_id' => InCharge::inRandomOrder()->first()->id,
            'name' => $this->faker->words,
            'deadline' => now(),
            'sort_num' => $this->faker->unique()->randomDigitNotNull,
            'contents' => $json_data,
        ];
    }
}
