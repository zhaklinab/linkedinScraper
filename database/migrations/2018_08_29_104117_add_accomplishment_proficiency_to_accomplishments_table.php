<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccomplishmentProficiencyToAccomplishmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accomplishments', function (Blueprint $table) {
            $table->string('accomplishment_proficiency')->after('accomplishment_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accomplishments', function (Blueprint $table) {
            $table->dropColumn('accomplishment_proficiency');
        });
    }
}
