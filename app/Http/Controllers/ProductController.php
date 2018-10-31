<?php

namespace App\Http\Controllers;

use App\Category;
use App\Facades\ProdRepo;
use App\Product;
use App\ProductAttributes;
use App\Repositories\ProductAttributesRepository;
use Illuminate\Http\Request;
use App\Repositories\ProductRepository;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductAttributeRequest;


class ProductController extends Controller
{
    public function index(Request $request){
        $response = ProdRepo::getProducts($request->pagination ?? 5);
        if($request->type !=null){
            $response = ProdRepo::convert_to($request->type, $response);
        }
        return view('layouts.product.index',['products' => $response]);//response()->json($response);
    }

    public function show($product){
        $response = ProdRepo::getById($product);
        return view('layouts.product.show', ['product' => $response]);//response()->json($response);
    }

    public function create(){
        $categories = Category::all();
        return view('layouts.product.create',['categories' => $categories]);
    }

    public function store(ProductRequest $request){
        ProdRepo::createRecord($request->all());
        return redirect('/products');//response()->json($product);
    }

    public function edit($product){
        $result = ProdRepo::getById($product);
        $categories = Category::all();
        return view('layouts.product.edit',['product' => $result, 'categories' => $categories]);
    }

    public function update(ProductRequest $request, $product){
        ProdRepo::updateRecord($request->all(), $product);
        return redirect('/products');//response()->json($product);
    }

    public function destroy($product){
        ProdRepo::delete($product);
        return redirect('/products');//response()->json($product);
    }

    public function addAttr($product_id){
        $product = ProdRepo::getById($product_id);
        return view('layouts.product.addAttributes', ['product' => $product, ]);
    }

    public function storeAttr(ProductAttributeRequest $request, ProductAttributesRepository $attributesRepository){
        $attributesRepository->create($request->all());
        return redirect()->back();//response()->json($product);
    }

    public function delAttr($id, ProductAttributesRepository $attributesRepository){
        $attributesRepository->delete($id);
        return redirect()->back();
    }

    public function editAttr($id, ProductAttributesRepository $attributesRepository){
        $result = $attributesRepository->getById($id);
        return response()->json($result);
    }

    public function changeAttr($id, ProductAttributeRequest $request, ProductAttributesRepository $attributesRepository){
        $result = $attributesRepository->update($id, $request->all());
       return response()->json($result);
    }
}
