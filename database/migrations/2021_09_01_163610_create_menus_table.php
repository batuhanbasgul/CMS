<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('uid');
            $table->timestamps();

            $table->unsignedInteger('order');
            $table->string('parent_menu_uid')->default('0');
            $table->integer('rank')->default(1);
            $table->string('menu_name');
            $table->boolean('is_mobile_active')->default(1);
            $table->boolean('is_desktop_active')->default(1);
            $table->string('lang_code');
            $table->string('menu_code');
            $table->string('content_slug');
            $table->unsignedBigInteger('content_id');
            $table->string('menu_logo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
