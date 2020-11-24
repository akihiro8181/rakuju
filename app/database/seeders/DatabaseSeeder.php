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
        // 動かない
        // \App\Models\User::factory()->create();
        
        // 問題なく動く
        \App\Models\School::factory()->create();

        // 動くけどUserFactroyが動かない都合でコメントアウト
        // ログイン画面でユーザー登録をしてから手動でコメント外して使う
        // \App\Models\Classwork::factory()->create();
        // \App\Models\InCharge::factory()->create();
        // \App\Models\Attendance::factory()->create();
        // \App\Models\Tutor::factory()->create();

        // まだ動かない
        // \App\Models\ClassworkTask::factory()->create();
    }
}
