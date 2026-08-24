<?php

namespace App\Http\Controllers;

use App\Models\Product;

class SitemapController extends Controller
{
    public function __invoke()
    {
        $urls = collect([
            ['loc' => route('home'), 'priority' => '1.0'],
            ['loc' => route('products.index'), 'priority' => '0.9'],
            ['loc' => route('deals'), 'priority' => '0.7'],
            ['loc' => route('delivery'), 'priority' => '0.6'],
            ['loc' => route('returns'), 'priority' => '0.6'],
            ['loc' => route('faq'), 'priority' => '0.5'],
            ['loc' => route('contacts'), 'priority' => '0.6'],
            ['loc' => route('about'), 'priority' => '0.5'],
        ])->merge(
            Product::active()->select(['slug', 'updated_at'])->get()->map(fn (Product $product) => [
                'loc' => route('products.show', $product->slug),
                'lastmod' => $product->updated_at?->toDateString(),
                'priority' => '0.8',
            ])
        );

        return response()
            ->view('sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
