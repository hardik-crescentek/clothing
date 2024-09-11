<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsInMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->string('roll_per_mtr')->after('roll');
            $table->string('cut_wholesale_per_mtr')->after('cut_wholesale');
            $table->string('retail_per_mtr')->after('retail');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropColumn('roll_per_mtr');
            $table->dropColumn('cut_wholesale_per_mtr');
            $table->dropColumn('retail_per_mtr');
        });
    }
}
