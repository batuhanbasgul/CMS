<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('uid');
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('keywords')->nullable();
            $table->text('product_url')->nullable();
            $table->string('image_small_screen')->nullable();
            $table->string('image_large_screen')->nullable();
            $table->string('thumbnail_large_screen')->nullable();
            $table->string('thumbnail_small_screen')->nullable();
            $table->string('thumbnail2_large_screen')->nullable();
            $table->string('thumbnail2_small_screen')->nullable();
            $table->string('hero')->nullable();
            $table->string('price')->nullable();
            $table->string('product_no')->nullable();
            $table->integer('order');
            $table->boolean('is_active')->default(1);
            $table->string('lang_code');
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
        Schema::dropIfExists('products');
    }
}
