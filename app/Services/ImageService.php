<?php

namespace App\Services;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Upload multiple images for a product
     */
    public function uploadImages(Product $product, array $files): Collection
    {
        $images = collect();

        foreach ($files as $file) {
            /** @var UploadedFile $file */
            $filename = $this->generateUniqueFilename($file);
            $path = $file->storeAs('products', $filename, 'public');

            $image = Image::create([
                'product_id' => $product->id,
                'path' => $path,
            ]);

            $images->push($image);
        }

        return $images;
    }

    /**
     * Delete single image
     */
    public function deleteImage(int $imageId): bool
    {
        $image = Image::find($imageId);

        if (!$image) {
            return false;
        }

        Storage::disk('public')->delete($image->path);
        $image->delete();

        return true;
    }

    /**
     * Delete all images for a product
     */
    public function deleteProductImages(Product $product): void
    {
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }
    }

    /**
     * Generate unique filename for an uploaded file
     */
    protected function generateUniqueFilename(UploadedFile $file): string
    {
        $timestamp = time();
        $random = Str::random(6);
        $extension = $file->getClientOriginalExtension();

        return "{$timestamp}_{$random}.{$extension}";
    }
}