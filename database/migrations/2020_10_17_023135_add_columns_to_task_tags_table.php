<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToTaskTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task_tags', function (Blueprint $table) {
            $table->unsignedInteger('task_id')
                ->references('id')
                ->on('tasks')
                ->after('id');
            $table->unsignedInteger('tag_id')
                ->references('id')
                ->on('tags')
                ->after('task_id');
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task_tags', function (Blueprint $table) {
            $table->dropColumn('task_id');
            $table->dropColumn('tag_id');
        });
    }
}
