<?php

namespace App\Repositories;
use App\Facades\Currency;
use App\Product;
use App\Category;
use App\ProductAttributes;
use Illuminate\Http\Request;
use Exception;
use App\Repositories\ProductImageRepository;
use Illuminate\Support\Facades\Input;

class ProductRepository{

    public function getProducts($items = 5){
        if ($items == null){
            return Product::with('product_images')->paginate(10);
        }
       return Product::with('product_images')->paginate($items);
    }

    public function getProductsByCategory($category, $items = 5){
        if($category == "all"){
            return Product::paginate($items);
        }
        return Category::findOrFail($category)->products()->paginate($items);
    }

    public function getById($product){
        return Product::with('categories','product_images')->findOrFail($product);
    }

    public function createRecord($request){

        try{
           $product = Product::create($request);
               $product->categories()
                ->attach($request['categories'] ?? []);
//               if($request['images'])
//               {
//                   $imageRepository = new ProductImageRepository();
//                  return $imageRepository->appendImagesToProduct($product->id, $request);
//               }

        }catch (Exception $e) {
            report($e);
            return false;
        }
        return $product;
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
        $imageRepository = new ProductImageRepository();
        try{
            $attributesRepository->deleteByProduct($product);
        }catch (Exception $e) {
            report($e);
            return false;
        }
        $result = Product::findOrFail($product);
        try{
            $result->categories()->detach();
            if($result->has('product_images'))
            {
                $imageRepository->deleteImagesByProduct($product);
            }
            $result->delete();
        }catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function convert_to($type, $products){
        foreach ($products as $product){
            $product->price = Currency::convert($product->price, $product->currency, $type);
            $product->currency = $type;
        }
        return $products;
    }

}
