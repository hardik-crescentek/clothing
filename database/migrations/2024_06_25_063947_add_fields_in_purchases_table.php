<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsInPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->double('ex_rate')->after('status')->nullable();
            $table->double('total_yard')->default(0)->after('ex_rate');
            $table->double('import_tax')->default(0)->after('total_yard');
            $table->double('transport_shipping_paid')->default(0)->after('import_tax');
            $table->double('transport_shippment_cost_per_meter')->default(0)->after('transport_shipping_paid');
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
            $table->dropColumn('ex_rate');
            $table->dropColumn('total_yard');
            $table->dropColumn('import_tax');
            $table->dropColumn('transport_shipping_paid');
            $table->dropColumn('transport_shippment_cost_per_meter');
        });
    }
}
