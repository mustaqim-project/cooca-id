<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

/**
 * ImageService
 *
 * Converts uploaded images to WebP format using PHP GD for reduced file
 * size and faster page loads. Falls back to the original format for file
 * types that cannot be meaningfully converted (SVG, ICO, GIF).
 */
class ImageService
{
    /** Extensions that should NOT be converted to WebP */
    private const SKIP_EXTENSIONS = ['svg', 'ico', 'gif'];

    /** WebP encode quality (0–100). 85 gives excellent quality at ~60% of JPEG size. */
    private const WEBP_QUALITY = 85;

    // -------------------------------------------------------------------------
    // Public API
    // -------------------------------------------------------------------------

    /**
     * Store an uploaded image on a Laravel storage disk, converting to WebP
     * when possible.
     *
     * @param UploadedFile $file      Incoming file
     * @param string       $disk      Storage disk name (e.g. 'public')
     * @param string       $directory Directory within the disk  (e.g. 'blog/images')
     * @param string       $basename  Desired filename WITHOUT extension (e.g. 'my-post')
     * @return string                 Relative path on the disk   (e.g. 'blog/images/my-post.webp')
     */
    public function saveToStorage(
        UploadedFile $file,
        string $disk,
        string $directory,
        string $basename
    ): string {
        $ext = strtolower($file->getClientOriginalExtension());

        if ($this->shouldSkip($ext)) {
            return $file->storeAs($directory, "{$basename}.{$ext}", $disk);
        }

        $webpData = $this->convertToWebP($file->getRealPath(), $ext);
        $path     = ltrim($directory, '/') . '/' . $basename . '.webp';
        Storage::disk($disk)->put($path, $webpData);

        return $path;
    }

    /**
     * Save an uploaded image directly into an absolute public directory,
     * converting to WebP when possible.
     *
     * @param UploadedFile $file      Incoming file
     * @param string       $publicDir Absolute filesystem path to the target directory
     * @param string       $basename  Desired filename WITHOUT extension
     * @return string                 The saved filename WITH extension (e.g. '123456_logo.webp')
     */
    public function saveToPublic(
        UploadedFile $file,
        string $publicDir,
        string $basename
    ): string {
        $ext = strtolower($file->getClientOriginalExtension());

        if (!is_dir($publicDir)) {
            mkdir($publicDir, 0755, true);
        }

        if ($this->shouldSkip($ext)) {
            $filename = "{$basename}.{$ext}";
            $file->move($publicDir, $filename);
            return $filename;
        }

        $filename = "{$basename}.webp";
        $webpData = $this->convertToWebP($file->getRealPath(), $ext);
        file_put_contents($publicDir . DIRECTORY_SEPARATOR . $filename, $webpData);

        return $filename;
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    private function shouldSkip(string $ext): bool
    {
        return in_array($ext, self::SKIP_EXTENSIONS, true);
    }

    /**
     * Read the source image with GD and return the raw WebP binary string.
     *
     * @throws RuntimeException when GD cannot decode the source.
     */
    private function convertToWebP(string $sourcePath, string $ext): string
    {
        $gdImage = match ($ext) {
            'jpg', 'jpeg' => @imagecreatefromjpeg($sourcePath),
            'png'         => @imagecreatefrompng($sourcePath),
            'webp'        => @imagecreatefromwebp($sourcePath),
            default       => @imagecreatefromstring((string) file_get_contents($sourcePath)),
        };

        if ($gdImage === false) {
            throw new RuntimeException("GD could not decode image: {$sourcePath}");
        }

        // Preserve alpha channel when source is PNG or already WebP
        if (in_array($ext, ['png', 'webp'], true)) {
            imagepalettetotruecolor($gdImage);
            imagealphablending($gdImage, true);
            imagesavealpha($gdImage, true);
        }

        ob_start();
        imagewebp($gdImage, null, self::WEBP_QUALITY);
        $webpData = ob_get_clean();
        imagedestroy($gdImage);

        if ($webpData === false || $webpData === '') {
            throw new RuntimeException('imagewebp() produced empty output.');
        }

        return $webpData;
    }
}
