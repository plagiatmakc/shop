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
use App\Repositories\ProductRepository;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductAttributeRequest;
use Illuminate\Support\Facades\Session;
use App\Facades\Cart;
use App\Http\Requests\PaginationAndCurrencyRequest;

class CartController extends Controller
{

    public function index(CartImplementation $cart,PaginationAndCurrencyRequest $request)
    {
        return response()->json($cart); //view('cart', ['cart' => $cart]);
    }

    public function addToCart($id, ProductRepository $productRepository)
    {
        $product = $productRepository->getById($id)->unsetRelation('product_images')->unsetRelation('categories');
        Cart::add($product, $product->id);
        return response()->json(['product' => $product->id]);
    }

    public function delFromCart($id)
    {
        Cart::del($id);
        return back();//redirect('/cart');
    }

    public function removeFromCart($id){
        Cart::destroyItem($id);
        return back();
    }
}
