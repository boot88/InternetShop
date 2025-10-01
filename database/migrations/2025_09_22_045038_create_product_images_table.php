<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->onDelete('cascade');
            $table->string('image_path');
            $table->boolean('is_main')->default(false);
            $table->string('alt_text')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
            
            $table->index('product_id');
            $table->index('variant_id');
            
            // Удаляем check() constraint
            // $table->check('product_id is not null or variant_id is not null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};