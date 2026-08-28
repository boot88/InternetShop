<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeAdmin($request);
        $search = trim((string) $request->input('search', ''));
        $products = Product::query()
            ->with(['brand', 'stock'])
            ->withCount('images')
            ->when($search !== '', fn ($query) => $query->where(fn ($nested) => $nested
                ->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%")))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.products.index', compact('products', 'search'));
    }

    public function edit(Request $request, Product $product)
    {
        $this->authorizeAdmin($request);
        $product->load(['categories', 'images', 'stock', 'variants.stock']);
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'brands', 'categories'));
    }

    public function create(Request $request)
    {
        $this->authorizeAdmin($request);

        return view('admin.products.create', [
            'brands' => Brand::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin($request);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:255', 'unique:products,sku'],
            'model' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string', 'max:10000'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_price' => ['nullable', 'numeric', 'gt:price'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'categories' => ['array'],
            'categories.*' => ['integer', 'exists:categories,id'],
            'stock_quantity' => ['required', 'integer', 'min:0', 'max:1000000'],
            'images' => ['required', 'array', 'min:1', 'max:5'],
            'images.*' => ['image', 'mimes:jpeg,jpg,png,webp,avif', 'max:10240'],
        ]);

        $product = DB::transaction(function () use ($request, $data): Product {
            $baseSlug = Str::slug($data['name']) ?: Str::lower(Str::random(8));
            $slug = $baseSlug;
            $suffix = 2;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $baseSlug.'-'.$suffix++;
            }

            $product = Product::create([
                ...collect($data)->except(['categories', 'stock_quantity', 'images'])->all(),
                'slug' => $slug,
                'is_active' => $request->boolean('is_active', true),
                'is_featured' => $request->boolean('is_featured'),
                'price_verified_at' => now(),
            ]);
            $product->categories()->sync($data['categories'] ?? []);
            Stock::create(['product_id' => $product->id, 'quantity' => $data['stock_quantity']]);
            foreach ($request->file('images', []) as $position => $image) {
                $product->images()->create([
                    'image_path' => $this->normalizeImage($image),
                    'alt_text' => $product->name,
                    'order' => $position,
                    'is_main' => $position === 0,
                ]);
            }

            return $product;
        });

        return redirect()->route('admin.products.edit', $product)->with('success', 'Товар создан. Добавьте подробные характеристики.');
    }

    public function update(Request $request, Product $product)
    {
        $this->authorizeAdmin($request);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:255', 'unique:products,sku,'.$product->id],
            'model' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string', 'max:10000'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_price' => ['nullable', 'numeric', 'gt:price'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'categories' => ['array'],
            'categories.*' => ['integer', 'exists:categories,id'],
            'stock_quantity' => ['required', 'integer', 'min:0', 'max:1000000'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'dimensions' => ['nullable', 'string', 'max:255'],
            'country_of_origin' => ['nullable', 'string', 'max:120'],
            'warranty_months' => ['nullable', 'integer', 'min:0', 'max:240'],
            'package_contents' => ['nullable', 'string', 'max:5000'],
            'price_verified_at' => ['nullable', 'date'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'images' => ['array', 'max:5'],
            'images.*' => ['image', 'mimes:jpeg,jpg,png,webp,avif', 'max:10240'],
            'main_image_id' => ['nullable', 'integer'],
        ]);

        DB::transaction(function () use ($request, $product, $data): void {
            $priceChanged = (float) $product->price !== (float) $data['price']
                || (float) ($product->compare_price ?? 0) !== (float) ($data['compare_price'] ?? 0);
            $payload = [
                ...collect($data)->except(['categories', 'stock_quantity', 'images', 'main_image_id'])->all(),
                'slug' => $product->slug ?: Str::slug($data['name']),
                'is_active' => $request->boolean('is_active'),
                'is_featured' => $request->boolean('is_featured'),
            ];
            if ($priceChanged && empty($payload['price_verified_at'])) {
                $payload['price_verified_at'] = now();
            }
            $product->update($payload);
            $product->categories()->sync($data['categories'] ?? []);
            Stock::updateOrCreate(
                ['product_id' => $product->id, 'variant_id' => null],
                ['quantity' => $data['stock_quantity']]
            );

            foreach ($request->file('images', []) as $position => $image) {
                $product->images()->create([
                    'image_path' => $this->normalizeImage($image),
                    'alt_text' => $product->name,
                    'order' => $product->images()->max('order') + $position + 1,
                    'is_main' => false,
                ]);
            }

            $mainImageId = $request->integer('main_image_id');
            if ($mainImageId && $product->images()->whereKey($mainImageId)->exists()) {
                $product->images()->update(['is_main' => false]);
                $product->images()->whereKey($mainImageId)->update(['is_main' => true]);
            } elseif (! $product->images()->where('is_main', true)->exists()) {
                $product->images()->orderBy('order')->first()?->update(['is_main' => true]);
            }
        });

        return back()->with('success', 'Товар обновлён.');
    }

    public function destroyImage(Request $request, Product $product, ProductImage $image)
    {
        $this->authorizeAdmin($request);
        abort_unless($image->product_id === $product->id, 404);
        $wasMain = $image->is_main;
        $path = ltrim((string) $image->image_path, '/');
        $image->delete();

        if (str_starts_with($path, 'images/products/')) {
            File::delete(public_path($path));
        }
        if ($wasMain) {
            $product->images()->orderBy('order')->first()?->update(['is_main' => true]);
        }

        return back()->with('success', 'Изображение удалено.');
    }

    private function normalizeImage(UploadedFile $file): string
    {
        if (! function_exists('imagecreatefromstring') || ! function_exists('imagewebp')) {
            throw ValidationException::withMessages(['images' => 'Для оптимизации изображений на сервере требуется расширение PHP GD с поддержкой WebP.']);
        }

        $source = @imagecreatefromstring((string) file_get_contents($file->getRealPath()));
        if (! $source) {
            throw ValidationException::withMessages(['images' => 'Не удалось прочитать загруженное изображение.']);
        }

        $sourceWidth = imagesx($source);
        $sourceHeight = imagesy($source);
        $canvasSize = 1200;
        $scale = min($canvasSize / $sourceWidth, $canvasSize / $sourceHeight);
        $width = max(1, (int) round($sourceWidth * $scale));
        $height = max(1, (int) round($sourceHeight * $scale));
        $x = (int) (($canvasSize - $width) / 2);
        $y = (int) (($canvasSize - $height) / 2);

        $canvas = imagecreatetruecolor($canvasSize, $canvasSize);
        $white = imagecolorallocate($canvas, 255, 255, 255);
        imagefill($canvas, 0, 0, $white);
        imagecopyresampled($canvas, $source, $x, $y, 0, 0, $width, $height, $sourceWidth, $sourceHeight);

        $directory = public_path('images/products');
        File::ensureDirectoryExists($directory);
        $filename = Str::uuid().'.webp';
        $destination = $directory.DIRECTORY_SEPARATOR.$filename;
        $saved = imagewebp($canvas, $destination, 82);
        imagedestroy($source);
        imagedestroy($canvas);

        if (! $saved) {
            throw ValidationException::withMessages(['images' => 'Не удалось сохранить оптимизированное изображение.']);
        }

        return 'images/products/'.$filename;
    }

    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()?->isAdmin(), 403);
    }
}
