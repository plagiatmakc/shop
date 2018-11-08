<?php

namespace App\Http\Controllers;

use App\CartImplementation;
use App\Category;
use App\Facades\ProductsRepository;
use App\Product;
use App\ProductAttributes;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductAttributesRepository;
use Illuminate\Http\Request;


class PageController extends Controller
{
    public function index(Request $request, CategoryRepository $categoryRepository, CartImplementation $cart)
    {
        $response = ProductsRepository::getProductsByCategory($request->category ?? "all", $request->pagination ?? 5);
        if ($request->type != null) {
            $response = ProductsRepository::convert_to($request->type, $response);
        }
        return view('welcome', [
            'products' => $response,
            'categories' => $categoryRepository->allWithRelations(),
            'cart' => json_encode($cart)
        ]);
    }

    public function showProduct($id, $sku)
    {

        $product = ProductsRepository::getById($id);
        $attr = $product->product_attributes()
            ->where('sku', $sku)
            ->first();

        //  dd($attr);
        return view('showProduct', ['product' => $product, 'attr' => $attr]);
    }

}