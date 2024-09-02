<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsPaymentTermAndCreditDaysInOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_term')->nullable()->after('order_date');
            $table->string('credit_day')->nullable()->after('payment_term');
            $table->string('entered_by')->nullable()->after('credit_day');
            $table->string('arranged_by')->nullable()->after('entered_by');
            $table->string('inspected_by')->nullable()->after('arranged_by');
            $table->string('delivered_by')->nullable()->after('inspected_by');
            $table->dateTime('delivered_date')->nullable()->after('delivered_by');
            $table->string('total_number_of_items')->nullable()->after('delivered_date');
            $table->string('approximate_weight')->nullable()->after('total_number_of_items');
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
            $table->dropColumn('payment_term');
            $table->dropColumn('credit_day');
            $table->dropColumn('entered_by');
            $table->dropColumn('arranged_by');
            $table->dropColumn('inspected_by');
            $table->dropColumn('delivered_by');
            $table->dropColumn('delivered_date');
            $table->dropColumn('total_number_of_items');
            $table->dropColumn('approximate_weight');
        });
    }
}
