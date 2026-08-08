<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Deliberately built on PHP's bundled GD extension rather than a
 * Composer package (intervention/image, etc.) — this environment has
 * no Packagist access to verify a package installs cleanly, and GD
 * ships with virtually every PHP install, so this has zero new
 * dependencies. Swap for Intervention/Image later if more advanced
 * processing (format conversion to WebP, smart cropping) is wanted;
 * every call site only depends on this class's public method, not on
 * GD directly.
 */
class ImageOptimizer
{
    private const MAX_WIDTH = 1600;
    private const JPEG_QUALITY = 80;

    /**
     * Resizes (preserving aspect ratio, only ever shrinking) and
     * re-compresses an uploaded image, then stores it. Returns the
     * stored path, matching what `$file->store()` would have returned,
     * so call sites need no change beyond swapping the method call.
     * Falls back to a plain, unoptimized store if GD or the mime type
     * isn't supported — never blocks an upload over an optimization failure.
     */
    public function storeOptimized(UploadedFile $file, string $directory, string $disk = 'public'): string
    {
        if (! extension_loaded('gd') || ! in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/webp'], true)) {
            return $file->store($directory, $disk);
        }

        try {
            [$width, $height] = getimagesize($file->getRealPath());
            $source = $this->createImageFromFile($file->getRealPath(), $file->getMimeType());

            if (! $source) {
                return $file->store($directory, $disk);
            }

            if ($width > self::MAX_WIDTH) {
                $newHeight = (int) round($height * (self::MAX_WIDTH / $width));
                $resized = imagecreatetruecolor(self::MAX_WIDTH, $newHeight);
                imagecopyresampled($resized, $source, 0, 0, 0, 0, self::MAX_WIDTH, $newHeight, $width, $height);
                imagedestroy($source);
                $source = $resized;
            }

            $path = trim($directory, '/') . '/' . Str::random(40) . '.jpg';

            ob_start();
            imagejpeg($source, null, self::JPEG_QUALITY);
            $contents = ob_get_clean();
            imagedestroy($source);

            Storage::disk($disk)->put($path, $contents);

            return $path;
        } catch (\Throwable $e) {
            Log::warning("Image optimization failed, storing original: {$e->getMessage()}");
            return $file->store($directory, $disk);
        }
    }

    private function createImageFromFile(string $path, string $mimeType)
    {
        return match ($mimeType) {
            'image/jpeg' => imagecreatefromjpeg($path),
            'image/png' => imagecreatefrompng($path),
            'image/webp' => imagecreatefromwebp($path),
            default => null,
        };
    }
}
