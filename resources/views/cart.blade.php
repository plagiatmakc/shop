@extends('layouts.app')

@section('content')
    <div class="container">
   @if(!$cart->items)
    <p>Your cart is empty!!!</p>
   @else
       <table class="table">
           <thead>
           <tr>
               <th scope="col">Name</th>
               <th scope="col">Price</th>
               <th scope="col">Quantity</th>
               <th scope="col">Summary</th>
               <th scope="col">Operations</th>
           </tr>
           </thead>
           <tbody>
           @foreach($cart->items as $item)
               <tr>
                   <td>{{$item['item']->name}}</td>
                   <td>{{$item['item']->price}}</td>
                   <td>{{$item['qty']}}</td>
                   <td>{{$item['price']}}</td>
                   <td>
                       <div>
                           <a class="btn btn-info" href="/add-to-cart/{{$item['item']->id}}">
                               <i class="fa fa-plus-circle" aria-hidden="true"></i>
                           </a>
                           <a class="btn btn-warning" href="/del-from-cart/{{$item['item']->id}}">
                               <i class="fa fa-minus-circle" aria-hidden="true"></i>
                           </a>
                           <a class="btn btn-danger" href="/remove-from-cart/{{$item['item']->id}}" data-token="{{ csrf_token() }}">
                               Remove
                           </a>
                       </div>
                   </td>

           @endforeach
           </tbody>
       </table>
   @endif
        <hr>
        <div >
           <h3 class="text-right">Total price: {{$cart->totalPrice}}</h3>
        </div>
    </div>
@endsection
