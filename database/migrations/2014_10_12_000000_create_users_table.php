<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('user_role')->default('admin');
            $table->boolean('is_active')->default(1);
            $table->string('profile_image')->nullable();
            $table->string('title')->nullable();
            $table->boolean('sidebar_dark')->default(0);
            $table->boolean('header_dark')->default(0);
            $table->boolean('theme_dark')->default(0);
            $table->string('theme_preference')->default('default');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
