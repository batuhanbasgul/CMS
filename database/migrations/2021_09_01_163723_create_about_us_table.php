<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAboutUsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('about_us', function (Blueprint $table) {
            $table->id();
            $table->string('uid');
            $table->timestamps();

            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->string('menu_name');
            $table->integer('card_limit');
            $table->text('keywords')->nullable();
            $table->string('image_large_screen')->nullable();
            $table->string('image_small_screen')->nullable();
            $table->string('hero')->nullable();
            $table->string('video_embed_code')->nullable();
            $table->string('lang_code');
            $table->string('menu_code');
            $table->string('slug');
            $table->boolean('is_desktop_active')->default(1);
            $table->boolean('is_mobile_active')->default(1);
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('about_us');
    }
}
