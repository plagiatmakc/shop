<?php
namespace App\Repositories;
use App\ProductAttributes;
use Illuminate\Http\Request;
use Exception;

class ProductAttributesRepository{

    public function getById($attribute_id){
        return ProductAttributes::findOrFail($attribute_id);

    }

    public function create($request){
        try{
            ProductAttributes::create($request);
        }catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function update($id, $request){
        try{
            ProductAttributes::findOrFail($id)->update($request);
        }catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function delete($attribute_id){
        try{
            ProductAttributes::findOrFail($attribute_id)->delete();
        }catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function deleteByProduct($product){
        try{
            ProductAttributes::where('product_id', $product )->delete();
        }catch (Exception $e) {
            report($e);
            return false;
        }
    }
}
