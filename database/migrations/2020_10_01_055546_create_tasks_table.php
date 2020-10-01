<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('owner_id')
                ->references('id')
                ->on('users')
                ->nullable();
            $table->unsignedInteger('author_id')
                ->references('id')
                ->on('users')
                ->nullable();
            $table->unsignedInteger('status_id')
                ->references('id')
                ->on('task_statuses')
                ->default(1);
            $table->unsignedInteger('priority_id')
                ->references('id')
                ->on('task_priorities')
                ->default(1);
            $table->unsignedInteger('source_id')
                ->references('id')
                ->on('task_creation_types')
                ->default(1);
            $table->string('title');
            $table->string('description');
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
        Schema::dropIfExists('tasks');
    }
}
