<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\School::factory()->create();
        \App\Models\InCharge::factory()->create();
        \App\Models\Attendance::factory()->create();
        \App\Models\Classwork::factory()->create();
        // \App\Models\ClassworkTask::factory()->create();
    }
}
