@extends('layouts.app')

@section('content')


    <div class="container">
        <div class="card" style="width: 18rem;">
            {{--<img class="card-img-top" src="..." alt="Card image cap">--}}
            <div class="card-body">
                <h5 class="card-title">{{$product->name}}</h5>

                @foreach($product->categories as $category)
                    <h6 class="card-subtitle mb-2 text-muted">{{$category->title}}</h6>
                @endforeach

                <p class="card-text">{{$attr->size}}</p>
                <p class="card-text">{{$attr->price}} {{$product->currency}}</p>
                <a href="/add-to-cart/{{$attr->id}}" class="btn btn-warning">add to cart</a>
            </div>
        </div>
        <hr>
            @foreach($product->product_attributes as $attribute)
                <a class="btn btn-outline-primary" href="/show-product/{{$product->id}}/{{$attribute->sku}}">
                    {{$attribute->sku}} {{$attribute->size}}  {{$attribute->price}}
                </a>
            @endforeach
    </div>


@endsection
