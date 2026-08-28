<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('model')->nullable()->after('sku');
            $table->string('country_of_origin')->nullable()->after('dimensions');
            $table->unsignedSmallInteger('warranty_months')->nullable()->after('country_of_origin');
            $table->text('package_contents')->nullable()->after('warranty_months');
            $table->timestamp('price_verified_at')->nullable()->after('package_contents');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('stock_restored_at')->nullable()->after('payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['model', 'country_of_origin', 'warranty_months', 'package_contents', 'price_verified_at']);
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('stock_restored_at');
        });
    }
};
