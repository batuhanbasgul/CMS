<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('uid');
            $table->string('name');
            $table->text('keywords')->nullable();
            $table->string('parent_category_uid');
            $table->text('description')->nullable();
            $table->string('image_small_screen')->nullable();
            $table->string('image_large_screen')->nullable();
            $table->string('hero')->nullable();
            $table->string('lang_code');
            $table->integer('order');
            $table->integer('rank')->default(1);
            $table->boolean('is_active')->default(1);
            $table->string('slug');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_categories');
    }
}
