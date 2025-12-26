<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    /**
     * Get all products
     */
    public function getAllProducts(): Collection
    {
        return Product::with('images')->get();
    }

    /**
     * Get single product by ID
     */
    public function getProductById(int $id): ?Product
    {
        return Product::with('images')->find($id);
    }

    /**
     * Create new product
     */
    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }

    /**
     * Update existing product
     */
    public function updateProduct(int $id, array $data): ?Product
    {
        
        $product = Product::find($id);

        if (!$product) {
            return null;
        }

        $product->update($data);

        return $product->load('images');
    }

    /**
     * Delete product
     */
    public function deleteProduct(int $id): bool
    {
        $product = Product::find($id);

        if (!$product) {
            return false;
        }

        $product->delete();

        return true;
    }
}
