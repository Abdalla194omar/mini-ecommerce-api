<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\ImageService;

class ImageController extends Controller
{
    protected ImageService $imageService;
    protected ProductService $productService;

    public function __construct(
        ImageService $imageService,
        ProductService $productService
    ) {
        $this->imageService = $imageService;
        $this->productService = $productService;
        
        // Only authenticated admins can upload/delete images
        $this->middleware('auth:api');
    }

    /**
     * Upload images for a product
     */
    public function upload(Request $request, int $productId): JsonResponse
    {
        $product = $this->productService->getProductById($productId);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $this->authorize('update', $product);

        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|max:2048'
        ]);

        $images = $this->imageService->uploadImages($product, $request->file('images'));

        return response()->json([
            'message' => 'Images uploaded successfully',
            'data' => $images
        ], 201);
    }

    /**
     * Delete single image
     */
    public function destroy(int $imageId): JsonResponse
    {
        $deleted = $this->imageService->deleteImage($imageId);

        if (!$deleted) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        return response()->json(['message' => 'Image deleted successfully'], 200);
    }
}