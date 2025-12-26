<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;

        $this->middleware('auth:api')->only(['store', 'update', 'destroy']);
    }

    /**
     * Display a listing of products
     */
    public function index(): JsonResponse
    {
        $products = $this->productService->getAllProducts();

        return response()->json($products, 200);
    }

    /**
     * Display single product
     */
    public function show(int $id): JsonResponse
    {
        $product = $this->productService->getProductById($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($product, 200);
    }

    /**
     * Store new product (Admin only)
     */
    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Product::class);
        
        $product = $this->productService->createProduct($request->all());

        return response()->json($product, 201);
    }

    /**
     * Update product (Admin only)
     */
    public function update(Request $request, int $id): JsonResponse
    {

        $product = $this->productService->getProductById($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $this->authorize('update', $product);

        $product = $this->productService->updateProduct($id, $request->all());

        return response()->json($product, 200);
    }

    /**
     * Delete product (Admin only)
     */
    public function destroy(int $id): JsonResponse
    {

        $product = $this->productService->getProductById($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $this->authorize('delete', $product);

        $deleted = $this->productService->deleteProduct($id);

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}
