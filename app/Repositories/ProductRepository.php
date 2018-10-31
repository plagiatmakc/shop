<?php

namespace App\Repositories;
use App\Facades\Currency;
use App\Product;
use App\Category;
use App\ProductAttributes;
use Illuminate\Http\Request;
use Exception;

class ProductRepository{

    public function getProducts($items = 5){
       return Product::paginate($items);
    }

    public function getProductsByCategory($category, $items = 5){
        if($category == "all"){
            return Product::paginate($items);
        }
        return Category::findOrFail($category)->products()->paginate($items);
    }

    public function getById($product){
        return Product::findOrFail($product);
    }

    public function createRecord($request){
        try{
            Product::create($request)
                ->categories()
                ->attach($request['categories'] ?? []);
        }catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function updateRecord($request, $product){
        $result = Product::findOrFail($product);
        try{
            $result->update($request);
        } catch (Exception $e) {
            report($e);
            return false;
        }
        $result->categories()
            ->sync($request['categories'] ?? []);
    }

    public function delete($product){
        $attributesRepository = new ProductAttributesRepository;
        try{
            $attributesRepository->deleteByProduct($product);
        }catch (Exception $e) {
            report($e);
            return false;
        }
        Product::findOrFail($product)->delete();
    }

    public function convert_to($type, $products){
        foreach ($products as $product){
            $product->price = Currency::convert($product->price, $product->currency, $type);
            $product->currency = $type;
        }
        return $products;

    }

}
