<?php

namespace App\Http\Controllers;

use App\CartImplementation;
use App\Category;
use App\Facades\ProdRepo;
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


class CartController extends Controller
{

    public function index(CartImplementation $cart)
    {
        return view('cart', ['cart' => $cart]);
    }
    public function addToCart($id, ProductAttributesRepository $attributesRepository)
    {
        $attribute = $attributesRepository->getById($id);
        Cart::add($attribute, $attribute->id);
        return back();
    }

    public function delFromCart($id)
    {
        Cart::del($id);
        return redirect('/cart');
    }

    public function removeFromCart($id){
        Cart::destroyItem($id);
        return back();
    }
}
