<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImageManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_managers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->enum('type', [
                'kullanıcı_resim',
                'website_favicon',
                'website_banner',
                'popup_resim_geniş',
                'popup_resim_mobil',
                'dil_icon',
                'header_resim_geniş',
                'header_resim_mobil',
                'footer_resim_geniş',
                'footer_resim_mobil',
                'menü_icon',
                'slider_resim_geniş',
                'slider_resim_mobil',
                'hakkımızda_resim_geniş',
                'hakkımızda_resim_mobil',
                'hakkımızda_banner',
                'referans_içerik_resim_geniş',
                'referans_içerik_resim_mobil',
                'referans_içerik_banner',
                'referans_resim_geniş',
                'referans_resim_mobil',
                'referans_thumbnail_geniş',
                'referans_thumbnail_mobil',
                'ucret_içerik_resim_geniş',
                'ucret_içerik_resim_mobil',
                'ucret_içerik_banner',
                'ucret_resim_geniş',
                'ucret_resim_mobil',
                'ucret_thumbnail_geniş',
                'ucret_thumbnail_mobil',
                'galeri_içerik_resim_geniş',
                'galeri_içerik_resim_mobil',
                'galeri_içerik_banner',
                'galeri_resim',
                'galeri_thumbnail',
                'ürün_içerik_resim_geniş',
                'ürün_içerik_resim_mobil',
                'ürün_içerik_banner',
                'ürün_kategori_içerik_resim_geniş',
                'ürün_kategori_içerik_resim_mobil',
                'ürün_kategori_içerik_banner',
                'ürün_resim_geniş',
                'ürün_resim_mobil',
                'ürün_thumbnail_geniş',
                'ürün_thumbnail_mobil',
                'ürün_thumbnail2_geniş',
                'ürün_thumbnail2_mobil',
                'ürün_banner',
                'ürün_resim',
                'duyuru_içerik_resim_geniş',
                'duyuru_içerik_resim_mobil',
                'duyuru_içerik_banner',
                'duyuru_resim_geniş',
                'duyuru_resim_mobil',
                'duyuru_thumbnail_geniş',
                'duyuru_thumbnail_mobil',
                'duyuru_banner',
                'duyuru_resim',
                'iletişim_resim_geniş',
                'iletişim_resim_mobil',
                'iletişim_banner',
                'yapım_aşaması',
                'front_bakim_aşaması',
                'back_bakim_aşaması',
                'sayfa_resim_geniş',
                'sayfa_resim_mobil',
                'sayfa_banner',
                'sayfa_resim',
                'sayfa_thumbnail_geniş',
                'sayfa_thumbnail_mobil',
                'hakkimizda_kart_resim_geniş',
                'hakkimizda_kart_resim_mobil',
                'hakkimizda_kart_thumbnail_geniş',
                'hakkimizda_kart_thumbnail_mobil',
            ]);
            $table->string('width')->default('1000');
            $table->string('height')->default('1000');
            $table->double('ratio')->default(1.0);
            $table->integer('quality')->default(90);
            $table->integer('file_size')->default(1048576000);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('image_managers');
    }
}
