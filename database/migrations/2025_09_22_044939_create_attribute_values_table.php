<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')->constrained()->onDelete('cascade');
            $table->string('value');
            $table->string('color_code', 7)->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
            
            $table->index('attribute_id');
            $table->index('value');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_values');
    }
};