<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAboutUsCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('about_us_cards', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('uid');
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->string('image_large_screen')->nullable();
            $table->string('image_small_screen')->nullable();
            $table->string('thumbnail_small_screen')->nullable();
            $table->string('thumbnail_large_screen')->nullable();
            $table->integer('order');
            $table->boolean('is_active')->default(1);
            $table->string('lang_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('about_us_cards');
    }
}
