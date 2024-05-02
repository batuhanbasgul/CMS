<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('references', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image_large_screen')->nullable();
            $table->string('image_small_screen')->nullable();
            $table->string('thumbnail_small_screen')->nullable();
            $table->string('thumbnail_large_screen')->nullable();
            $table->integer('order');
            $table->text('keywords')->nullable();
            $table->string('video_embed_code')->nullable();
            $table->string('reference_url')->nullable();
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
        Schema::dropIfExists('references');
    }
}
