<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMovedByAndTransportedByToPurchaseItemsWarehouseHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_items_warehouse_history', function (Blueprint $table) {
            $table->string('moved_by')->nullable()->after('current_warehouse_id'); 
            $table->string('transported_by')->nullable()->after('moved_by'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_items_warehouse_history', function (Blueprint $table) {
            $table->dropColumn('moved_by'); 
            $table->dropColumn('transported_by');
        });
    }
}
