<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->integer('supplier_id')->after('status');
            $table->string('made_in')->after('supplier_id');
            $table->string('currency')->after('made_in');
            $table->string('price')->after('currency');
            $table->string('roll')->after('price');
            $table->string('cut_wholesale')->after('roll');
            $table->string('retail')->after('cut_wholesale');
            $table->string('width_inch')->nullable()->after('retail');
            $table->string('width_cm')->nullable()->after('width_inch');
            $table->string('weight_gsm')->nullable()->after('width_cm');
            $table->string('weight_per_mtr')->nullable()->after('weight_gsm');
            $table->string('weight_per_yard')->nullable()->after('weight_per_mtr');
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
            $table->dropColumn('supplier_id');
            $table->dropColumn('made_in');
            $table->dropColumn('currency');
            $table->dropColumn('price');
            $table->dropColumn('roll');
            $table->dropColumn('cut_wholesale');
            $table->dropColumn('retail');
            $table->dropColumn('width_inch');
            $table->dropColumn('width_cm');
            $table->dropColumn('weight_gsm');
            $table->dropColumn('weight_per_mtr');
            $table->dropColumn('weight_per_yard');
        });
    }
}
