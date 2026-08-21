<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\ProductVariant;
use PHPUnit\Framework\TestCase;

class PricingTest extends TestCase
{
    public function test_product_final_price_is_the_current_sale_price(): void
    {
        $product = new Product(['price' => 99990, 'compare_price' => 109990]);

        $this->assertEquals(99990, $product->final_price);
        $this->assertTrue($product->has_discount);
    }

    public function test_variant_final_price_is_the_current_sale_price(): void
    {
        $variant = new ProductVariant(['price' => 54990, 'compare_price' => 59990]);

        $this->assertEquals(54990, $variant->final_price);
        $this->assertTrue($variant->has_discount);
        $this->assertSame(0, $variant->stock_quantity);
    }
}
