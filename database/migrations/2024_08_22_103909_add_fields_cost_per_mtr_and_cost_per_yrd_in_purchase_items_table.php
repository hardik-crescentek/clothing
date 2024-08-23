<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsCostPerMtrAndCostPerYrdInPurchaseItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->double('cost_per_mtr')->nullable()->default(null)->after('return_status');
            $table->double('cost_per_yrd')->nullable()->default(null)->after('cost_per_mtr');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->dropColumn('cost_per_mtr');
            $table->dropColumn('cost_per_yrd');
        });
    }
}
