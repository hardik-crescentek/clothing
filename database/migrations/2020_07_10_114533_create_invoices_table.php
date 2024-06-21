<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');
            $table->integer('order_id');
            $table->integer('customer_id');
            $table->integer('seller_id');
            $table->integer('sub_total');
            $table->integer('tax');
            $table->integer('discount');
            $table->integer('grand_total');
            $table->dateTime('invoice_date');
            $table->text('note')->nullable();
            $table->integer('status')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->integer('invoice_id');
            $table->integer('order_id');
            $table->integer('item_id');
            $table->integer('color_id');
            $table->integer('total_meter');
            $table->integer('total_rolls');
            $table->decimal('price');
            $table->timestamps();
        });
        Schema::create('invoice_item_rolls', function (Blueprint $table) {
            $table->id();
            $table->integer('invoice_item_id');
            $table->integer('invoice_id');
            $table->integer('roll_id');
            $table->integer('roll_no');
            $table->integer('meter');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoice_item_rolls');
    }
}
