<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsNoOfRollsAndNoOfBalesInPurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->double('no_of_rolls')->nullable()->default(null)->after('import_tax');
            $table->double('no_of_bales')->nullable()->default(null)->after('no_of_rolls');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('no_of_rolls');
            $table->dropColumn('no_of_bales');
        });
    }
}
