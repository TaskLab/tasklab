<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNullDefaultsToTaskRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task_relations', function (Blueprint $table) {
            $table->unsignedInteger('parent_id')
                ->references('id')
                ->on('tasks')
                ->nullable()
                ->default(null)
                ->change();
            $table->unsignedInteger('child_id')
                ->references('id')
                ->on('tasks')
                ->nullable()
                ->default(null)
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task_relations', function (Blueprint $table) {
            //
        });
    }
}
