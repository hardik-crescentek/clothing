<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');
            $table->dateTime('purchase_date');
            $table->integer('user_id');
            $table->integer('supplier_id')->nullable();
            $table->double('total_qty');
            $table->double('total_tax')->default(0);
            $table->double('shipping_cost')->default(0);
            $table->double('discount')->default(0);
            $table->double('price_usd');
            $table->double('thb_ex_rate');
            $table->double('price_thb');
            // $table->double('grand_total_usd');
            // $table->double('grand_total_thb');
            $table->string('payment_terms')->nullable();
            $table->string('purchase_type')->default('domestic');
            $table->text('note')->nullable();
            $table->string('status')->default('new');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->integer('purchase_id');
            $table->integer('material_id');
            $table->integer('color_id');
            $table->integer('roll_no');
            $table->string('article_no')->nullable();
            $table->string('batch_no')->nullable();
            $table->string('barcode');
            $table->string('qrcode');
            $table->string('width')->nullable();
            $table->double('qty');
            $table->double('available_qty');
            // $table->double('price_usd');
            // $table->double('thb_ex_rate');
            // $table->double('price_thb');
            // $table->double('total_tax')->default(0);
            // $table->double('shipping_cost')->default(0);
            // $table->double('discount')->default(0);            
            // $table->double('grand_total_usd');
            // $table->double('grand_total_thb');
            $table->string('status')->default('available')->nullable();
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
        Schema::dropIfExists('purchase');
        Schema::dropIfExists('purchase_items');
    }
}
