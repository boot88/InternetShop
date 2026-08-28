<?php

namespace Tests\Unit;

use App\Models\ProductImage;
use Tests\TestCase;

class ProductImageUrlTest extends TestCase
{
    public function test_it_resolves_a_bare_filename_from_public_images(): void
    {
        $image = new ProductImage(['image_path' => 'iphone15Pro.jfif']);

        $this->assertSame(asset('images/iphone15Pro.jfif'), $image->getUrl());
    }

    public function test_it_keeps_an_explicit_public_images_path(): void
    {
        $image = new ProductImage(['image_path' => 'public/images/iphone15Pro.jfif']);

        $this->assertSame(asset('images/iphone15Pro.jfif'), $image->getUrl());
    }

    public function test_it_uses_placeholder_for_an_unknown_relative_path(): void
    {
        $image = new ProductImage(['image_path' => 'products/missing.jpg']);

        $this->assertSame(asset('images/product-placeholder.svg'), $image->getUrl());
    }
}
