<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDispatcherIdToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('dispatcher_id')->after('note')->index();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->enum('status', ['Pending', 'Completed', 'Not Enough', 'Out of Stock', 'Damaged'])->default('Pending')->comment('Status of the order')->after('roll_id');
            $table->timestamp('status_date')->nullable()->after('status');
            $table->string('image')->nullable()->after('status_date');
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
            $table->dropColumn('dispatcher_id');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['status','status_date', 'image']);
        });
    }
}
