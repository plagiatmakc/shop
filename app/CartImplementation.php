<?php


namespace App;
use Illuminate\Support\Facades\Session;

class CartImplementation
{
    public $items = null;
    public $totalQuantity=0;
    public $totalPrice=0;

    public function __construct(){
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        if($oldCart) {
            $this->items = $oldCart->items;
            $this->totalQuantity = $oldCart->totalQuantity;
            $this->totalPrice = $oldCart->totalPrice;
        }

    }

    public function add($item, $id) {
        $storedItem = ['qty' => 0, 'price' => $item->price, 'item' => $item];
        if($this->items) {
            if(array_key_exists($id, $this->items)){
                $storedItem =$this->items[$id];
            }
        }
        $storedItem['qty']++;
        $storedItem['price']= $item->price * $storedItem['qty'];
        $this->items[$id]= $storedItem;
        $this->totalQuantity++;
        $this->totalPrice += $item->price;
        $this->totalPrice = round($this->totalPrice, 2);
        Session::put('cart', $this);
    }

    public function del($id){
        if($this->items[$id]['qty'] >1){
            $this->items[$id]['qty']--;
            $this->items[$id]['price'] = $this->items[$id]['item']->price * $this->items[$id]['qty'];
            $this->totalQuantity--;
            $this->totalPrice -= $this->items[$id]['item']->price;
            $this->totalPrice = round($this->totalPrice, 2);


        }else{
            $this->totalPrice -= $this->items[$id]['item']->price;
            $this->totalPrice = round($this->totalPrice, 2);
            unset($this->items[$id]);
        }
        Session::put('cart', $this);
    }

    public function destroyItem($id){
        $this->totalQuantity-=$this->items[$id]['qty'];
        $this->totalPrice -= $this->items[$id]['price'];
        $this->totalPrice = round($this->totalPrice, 2);
        unset($this->items[$id]);
        Session::put('cart', $this);
    }
}
