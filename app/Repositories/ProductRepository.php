<?php

namespace App\Repositories;

use App\Facades\Currency;
use App\Product;
use App\Category;
use App\ProductAttributes;
use Exception;
use App\Repositories\ProductImageRepository;


class ProductRepository
{

    public function getProducts($items_per_page = 5)
    {
        if ($items_per_page == null) {
            return Product::with('product_images')
                ->paginate(10);
        }
        return Product::with('product_images')
            ->paginate($items_per_page);
    }

    public function getProductsByCategory($category, $items = 5)
    {
        if ($category == "all") {
            return Product::with('product_images')
                ->orderBy('updated_at', 'desc')
                ->paginate($items);
        }
        return Category::findOrFail($category)
            ->products()
            ->with('product_images')
            ->paginate($items);
    }

    public function getById($product_id)
    {
        return Product::with('categories', 'product_images')
            ->findOrFail($product_id);
    }

    public function createRecord($request)
    {

        try {
            $product = Product::create($request);

        } catch (Exception $e) {
            report($e);
            return false;
        }
        return $product;
    }

    public function updateRecord($request, $product_id)
    {

        try {
            $product_to_update = Product::findOrFail($product_id);
            $product_to_update->update($request);
        } catch (Exception $e) {
            report($e);
            return false;
        }
        $product_to_update->categories()
            ->sync($request['categories'] ?? []);
        return $product_to_update;
    }

    public function delete($product_id)
    {
        $attributesRepository = new ProductAttributesRepository;
        $imageRepository = new ProductImageRepository;
        try {
            $attributesRepository->deleteByProduct($product_id);
        } catch (Exception $e) {
            report($e);
            return false;
        }

        try {
            $product_to_delete = Product::findOrFail($product_id);
            $product_to_delete->categories()->detach();
            if ($product_to_delete->has('product_images')) {
                $imageRepository->deleteImagesByProduct($product_id);
            }
            $product_to_delete->delete();
        } catch (Exception $e) {
            report($e);
            return response("Product is not found", 404);
        }
        return response("Success", 200);
    }

    public function convert_to($type, $products)
    {
        foreach ($products as $product) {
            $product->price = Currency::convert($product->price, $product->currency, $type);
            $product->currency = $type;
        }
        return $products;
    }

    public function single_convert_to($type, $product)
    {
            $product->price = Currency::convert($product->price, $product->currency, $type);
            $product->currency = $type;

        return $product;
    }

}
