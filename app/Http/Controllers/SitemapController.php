<?php

namespace App\Http\Controllers;

use App\Models\Product;

class SitemapController extends Controller
{
    public function robots()
    {
        return response(
            "User-agent: *\nAllow: /\nDisallow: /cart\nDisallow: /checkout\nDisallow: /admin\nDisallow: /profile\nDisallow: /orders\n\nSitemap: ".route('sitemap')."\n",
            200,
            ['Content-Type' => 'text/plain; charset=UTF-8']
        );
    }

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
            ['loc' => route('privacy'), 'priority' => '0.3'],
            ['loc' => route('terms'), 'priority' => '0.3'],
            ['loc' => route('requisites'), 'priority' => '0.4'],
        ])->merge(
            Product::active()->with('images')->select(['id', 'slug', 'updated_at'])->get()->map(fn (Product $product) => [
                'loc' => route('products.show', $product->slug),
                'lastmod' => $product->updated_at?->toDateString(),
                'priority' => '0.8',
                'images' => $product->images->map(fn ($image) => $image->getUrl())->all(),
            ])
        );

        return response()
            ->view('sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
