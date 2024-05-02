<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConstantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('constants', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('phone_1')->nullable();
            $table->string('phone_2')->nullable();
            $table->string('gsm_1')->nullable();
            $table->string('gsm_2')->nullable();
            $table->string('email_1')->nullable();
            $table->string('email_2')->nullable();
            $table->string('address_1')->nullable();
            $table->string('address_2')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_mail')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('subject')->nullable();
            $table->string('message')->nullable();
            $table->string('send_button')->nullable();
            $table->string('sent_message_success')->nullable();
            $table->string('sent_name_error')->nullable();
            $table->string('sent_mail_error')->nullable();
            $table->string('sent_subject_error')->nullable();
            $table->string('sent_message_error')->nullable();
            $table->string('sent_validation_error')->nullable();
            $table->string('quickmenu_title_1')->nullable();
            $table->string('quickmenu_title_2')->nullable();
            $table->string('copyright_description')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('date')->nullable();
            $table->string('author')->nullable();
            $table->string('keywords')->nullable();
            $table->string('read_more')->nullable();
            $table->string('detail')->nullable();
            $table->string('watch_video')->nullable();
            $table->string('buy_title')->nullable();
            $table->string('buy_subtitle')->nullable();
            $table->string('buy_price_button')->nullable();
            $table->string('buy_contact_button')->nullable();
            $table->string('close_lang')->nullable();
            $table->string('categories')->nullable();
            $table->string('all_products')->nullable();
            $table->string('product_no')->nullable();
            $table->string('product_info')->nullable();
            $table->string('product_name')->nullable();
            $table->string('product_price')->nullable();
            $table->string('product_keywords')->nullable();
            $table->string('no_product')->nullable();
            $table->string('cookie_title')->nullable();
            $table->string('cookie_description')->nullable();
            $table->string('cookie_button')->nullable();
            $table->string('cookie_button_refuse')->nullable();
            $table->string('link_title')->nullable();
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
        Schema::dropIfExists('constants');
    }
}
