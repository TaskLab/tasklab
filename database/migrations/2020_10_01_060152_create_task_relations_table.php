<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_relations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('task_id')
                ->references('id')
                ->on('tasks');
            $table->unsignedInteger('parent_id')
                ->references('id')
                ->on('tasks')
                ->nullabl();
            $table->unsignedInteger('child_id')
                ->references('id')
                ->on('tasks')
                ->nullable();
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
        Schema::dropIfExists('task_relations');
    }
}
