<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVatFieldInOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('vat_percentage')->after('is_api')->nullable();
            $table->decimal('vat_amount')->after('vat_percentage')->nullable();
            $table->decimal('grand_total')->after('vat_amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('vat_percentage');
            $table->dropColumn('vat_amount');
            $table->dropColumn('grand_total');
        });
    }
}
