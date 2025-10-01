<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->onDelete('cascade');
            $table->integer('quantity')->unsigned()->default(1);
            $table->decimal('price', 10, 2);
            $table->timestamps();
            
            $table->index('cart_id');
            $table->index('product_id');
            $table->index('variant_id');
            
            // Удаляем check() constraint
            // $table->check('product_id is not null or variant_id is not null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};