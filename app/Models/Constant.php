<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Constant extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone_1',  //İletişim
        'phone_2',
        'gsm_1',
        'gsm_2',
        'email_1',
        'email_2',
        'address_1',
        'address_2',
        'contact_name',      //contact form placeholders
        'contact_mail',
        'contact_phone',
        'subject',
        'message',
        'send_button',
        'sent_message_success',//
        'sent_name_error',
        'sent_mail_error',
        'sent_subject_error',
        'sent_message_error',
        'sent_validation_error',
        'quickmenu_title_1',    //Footer
        'quickmenu_title_2',
        'copyright_description',
        'phone',
        'email',
        'address',
        'title',        //Sayfalar
        'subtitle',
        'date',
        'author',
        'keywords',
        'read_more',
        'detail',
        'watch_video',
        'buy_title',
        'buy_subtitle',
        'buy_price_button',
        'buy_contact_button',
        'close_lang',
        'categories',   //Ürünler
        'all_products',
        'product_no',
        'product_info',
        'product_name',
        'product_price',
        'product_keywords',
        'no_product',
        'cookie_title',    //KVKK
        'cookie_description',
        'cookie_button',
        'cookie_button_refuse',
        'link_title',
        'lang_code' //Genel
    ];
}
