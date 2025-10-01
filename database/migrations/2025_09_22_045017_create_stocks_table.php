<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->integer('low_stock_threshold')->default(5);
            $table->string('location')->nullable();
            $table->timestamps();
            
            $table->index('product_id');
            $table->index('variant_id');
            $table->index('quantity');
            
            // Удаляем check() constraint - проверка будет на уровне приложения
            // $table->check('product_id is not null or variant_id is not null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};