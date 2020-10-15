<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_task', function (Blueprint $table) {
            $table->id();
            $table->foreignId('in_charge_id');
            $table->string("name");
            $table->timestamp('deadline', 0);
            $table->integer('sort_num');
            $table->json('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_task');
    }
}
