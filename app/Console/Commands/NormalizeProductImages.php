<?php

namespace App\Console\Commands;

use App\Models\ProductImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class NormalizeProductImages extends Command
{
    protected $signature = 'products:normalize-images {--dry-run : Show planned changes without writing files} {--force : Rebuild existing normalized files}';
    protected $description = 'Convert local product images to square 1200x1200 WebP files';

    public function handle(): int
    {
        if (! function_exists('imagecreatefromstring') || ! function_exists('imagewebp')) {
            $this->error('PHP GD with WebP support is required.');

            return self::FAILURE;
        }

        $directory = public_path('images/products/normalized');
        if (! $this->option('dry-run')) {
            File::ensureDirectoryExists($directory);
        }

        $converted = 0;
        $skipped = 0;
        ProductImage::with('product')->orderBy('id')->each(function (ProductImage $image) use ($directory, &$converted, &$skipped): void {
            $sourcePath = $this->resolveSource($image->image_path);
            if (! $sourcePath) {
                $this->warn("Skip #{$image->id}: remote or missing source");
                $skipped++;

                return;
            }

            $relative = 'images/products/normalized/product-'.$image->product_id.'-'.$image->id.'.webp';
            $destination = public_path($relative);
            $this->line(($this->option('dry-run') ? '[dry-run] ' : '').$image->image_path.' -> '.$relative);
            if ($this->option('dry-run')) {
                $converted++;

                return;
            }

            if ($this->option('force') || ! is_file($destination)) {
                $this->convert($sourcePath, $destination);
            }
            $image->update([
                'image_path' => $relative,
                'alt_text' => $image->alt_text ?: $image->product?->name,
            ]);
            $converted++;
        });

        $this->info("Converted: {$converted}; skipped: {$skipped}");

        return self::SUCCESS;
    }

    private function resolveSource(string $path): ?string
    {
        $path = ltrim(trim($path), '/');
        if ($path === '' || preg_match('~^https?://~i', $path)) {
            return null;
        }
        if (str_starts_with($path, 'public/')) {
            $path = substr($path, 7);
        }

        foreach ([public_path($path), public_path('images/'.$path)] as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    private function convert(string $sourcePath, string $destination): void
    {
        $source = @imagecreatefromstring((string) file_get_contents($sourcePath));
        if (! $source) {
            throw new \RuntimeException('Cannot read image: '.$sourcePath);
        }

        $sourceWidth = imagesx($source);
        $sourceHeight = imagesy($source);
        $size = 1200;
        $scale = min($size / $sourceWidth, $size / $sourceHeight);
        $width = max(1, (int) round($sourceWidth * $scale));
        $height = max(1, (int) round($sourceHeight * $scale));
        $canvas = imagecreatetruecolor($size, $size);
        imagefill($canvas, 0, 0, imagecolorallocate($canvas, 255, 255, 255));
        imagecopyresampled($canvas, $source, (int) (($size - $width) / 2), (int) (($size - $height) / 2), 0, 0, $width, $height, $sourceWidth, $sourceHeight);

        if (! imagewebp($canvas, $destination, 82)) {
            throw new \RuntimeException('Cannot write image: '.$destination);
        }
        imagedestroy($source);
        imagedestroy($canvas);
    }
}
