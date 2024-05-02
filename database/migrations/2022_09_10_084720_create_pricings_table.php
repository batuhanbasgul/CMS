<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pricings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('image_large_screen')->nullable();
            $table->string('image_small_screen')->nullable();
            $table->string('thumbnail_small_screen')->nullable();
            $table->string('thumbnail_large_screen')->nullable();
            $table->integer('order');
            $table->text('keywords')->nullable();
            $table->string('video_embed_code')->nullable();
            $table->string('pricing_url')->nullable();
            $table->boolean('is_active')->default(1);
            $table->string('lang_code');
            $table->string('slug');
            $table->string('entry1')->nullable();
            $table->string('entry2')->nullable();
            $table->string('entry3')->nullable();
            $table->string('entry4')->nullable();
            $table->string('entry5')->nullable();
            $table->string('entry6')->nullable();
            $table->string('entry7')->nullable();
            $table->string('entry8')->nullable();
            $table->string('entry9')->nullable();
            $table->string('entry10')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pricings');
    }
}
