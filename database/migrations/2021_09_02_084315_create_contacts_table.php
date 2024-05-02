<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('uid');
            $table->string('menu_name');
            $table->timestamps();

            $table->string('title')->nullable();
            $table->string('image_large_screen')->nullable();
            $table->string('image_small_screen')->nullable();
            $table->string('hero')->nullable();
            $table->text('keywords')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->string('phone1')->nullable();
            $table->string('phone2')->nullable();
            $table->string('gsm1')->nullable();
            $table->string('gsm2')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->text('google_maps')->nullable();
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
        Schema::dropIfExists('contacts');
    }
}
