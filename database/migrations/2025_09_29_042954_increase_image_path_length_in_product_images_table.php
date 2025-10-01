<?php
// database/migrations/xxxx_xx_xx_xxxxxx_increase_image_path_length_in_product_images_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IncreaseImagePathLengthInProductImagesTable extends Migration
{
    public function up()
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->string('image_path', 500)->change();
        });
    }

    public function down()
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->string('image_path', 255)->change();
        });
    }
}