<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnouncementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('uid');
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('author')->nullable();
            $table->text('description')->nullable();
            $table->text('keywords')->nullable();
            $table->text('short_description')->nullable();
            $table->text('announcement_date')->nullable();
            $table->string('image_large_screen')->nullable();
            $table->string('image_small_screen')->nullable();
            $table->string('thumbnail_small_screen')->nullable();
            $table->string('thumbnail_large_screen')->nullable();
            $table->string('hero')->nullable();
            $table->integer('order');
            $table->string('video_embed_code')->nullable();
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
        Schema::dropIfExists('announcements');
    }
}
