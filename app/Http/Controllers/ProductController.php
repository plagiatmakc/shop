<?php

namespace App\Http\Controllers;

use App\Category;
use App\Facades\ProductsRepository;
use App\Http\Requests\PaginationAndCurrencyRequest;
use App\Product;
use App\ProductAttributes;
use App\Repositories\ProductAttributesRepository;
use Illuminate\Http\Request;
use App\Repositories\ProductRepository;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductAttributeRequest;


class ProductController extends Controller
{
    public function index(PaginationAndCurrencyRequest $request)
    {
        $response = ProductsRepository::getProducts($request->pagination);
        if ($request->type != null) {
            $response = ProductsRepository::convert_to($request->type, $response);
        }
        return response()->json($response);//view('layouts.product.index',['products' => $response]);
    }

    public function show($product)
    {
        $response = ProductsRepository::getById($product);
        return view('layouts.product.show', ['product' => $response]);//response()->json($response);
    }

    public function create()
    {
        $categories = Category::all();
        return view('layouts.product.create', ['categories' => $categories]);
    }

    public function store(ProductRequest $request)
    {
        $product = ProductsRepository::createRecord($request->all());
        return response()->json($product);//redirect('/products');
    }

    public function edit($product)
    {
        return response()->json(ProductsRepository::getById($product));//view('layouts.product.edit',['product' => $result, 'categories' => $categories]);
    }

    public function update(ProductRequest $request, $product)
    {
        $result = ProductsRepository::updateRecord($request->all(), $product);
        return response()->json($result);//redirect('/products');//response()->json($product);
    }

    public function destroy($product)
    {
        return response()->json(ProductsRepository::delete($product));
    }

    public function addAttr($product_id)
    {
        $product = ProductsRepository::getById($product_id);
        return view('layouts.product.addAttributes', ['product' => $product,]);
    }

    public function storeAttr(ProductAttributeRequest $request, ProductAttributesRepository $attributesRepository)
    {
        $attributesRepository->create($request->all());
        return redirect()->back();//response()->json($product);
    }

    public function delAttr($id, ProductAttributesRepository $attributesRepository)
    {
        $attributesRepository->delete($id);
        return redirect()->back();
    }

    public function editAttr($id, ProductAttributesRepository $attributesRepository)
    {
        $result = $attributesRepository->getById($id);
        return response()->json($result);
    }

    public function changeAttr($id, ProductAttributeRequest $request, ProductAttributesRepository $attributesRepository)
    {
        $result = $attributesRepository->update($id, $request->all());
        return response()->json($result);
    }
}
