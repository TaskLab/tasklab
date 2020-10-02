<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrgKeyToOrganizationSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organization_settings', function (Blueprint $table) {
            $table->string('org_key', 8)
                ->unique()
                ->nullable()
                ->after('point_of_contact_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organization_settings', function (Blueprint $table) {
            $table->dropColumn('org_key');
        });
    }
}
