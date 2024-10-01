<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRollRetailAndCutWholesaleInClientArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_articles', function (Blueprint $table) {
            $table->decimal('roll_per_mtr', 10, 2)->nullable()->after('roll');
            $table->decimal('cut_wholesale_per_mtr', 10, 2)->nullable()->after('cut_wholesale');
            $table->decimal('retail_per_mtr', 10, 2)->nullable()->after('retail');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_articles', function (Blueprint $table) {
            $table->dropColumn('roll_mtr');
            $table->dropColumn('cut_wholesale_mtr');
            $table->dropColumn('retail_mtr');
        });
    }
}
