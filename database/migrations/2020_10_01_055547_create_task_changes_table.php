<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_changes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')
                ->references('id')
                ->on('users');
            $table->unsignedInteger('task_id')
                ->references('id')
                ->on('tasks');
            $table->string('field');
            $table->string('old_value');
            $table->string('new_value');
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
        Schema::dropIfExists('task_changes');
    }
}
