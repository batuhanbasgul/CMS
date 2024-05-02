<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePanelMaintenanceModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('panel_maintenance_models', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('title');
            $table->string('image')->nullable();
            $table->string('description')->nullable();
            $table->string('short_description')->nullable();
            $table->string('start_date')->nullable();
            $table->string('color')->default('#FFF');
            $table->boolean('is_active')->default(0);
            $table->text('lang_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('panel_maintenance_models');
    }
}
