<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseArticleColorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_article_colors', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_id')->index();
            $table->string('material_id')->index();
            $table->string('purchase_article_id')->index();
            $table->string('color')->index();
            $table->string('color_no');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_article_colors');
    }
}
