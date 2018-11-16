@extends('layouts.app')
{{--@extends('filters')--}}
@section('content')
<landing-page></landing-page>
        {{--<div class="container">--}}

{{--@if($cart)--}}
    {{--{{$cart}}--}}
{{--@endif--}}
            {{--@foreach($products->chunk(4) as $chunk_products)--}}
                {{--<div class="card-group">--}}
                {{--@foreach( $chunk_products as $product)--}}

                    {{--<div class="card" style="width: 18rem;">--}}
                        {{--<img class="card-img-top" src="..." alt="Card image cap">--}}
                        {{--<div class="card-body">--}}
                            {{--<h5 class="card-title">{{$product->name}}</h5>--}}

                            {{--@foreach($product->categories as $category)--}}
                                {{--<h6 class="card-subtitle mb-2 text-muted">{{$category->title}}</h6>--}}
                            {{--@endforeach--}}
                            {{--<p class="card-text">{{$product->price}} {{$product->currency}}</p>--}}

                            {{--<div class="card-footer">--}}
                            {{--<a href="/show-product/{{$product->id}}/{{$product->product_attributes->first()['sku']}}" class="btn btn-warning">View</a>--}}
                            {{--<a href="/add-to-cart/{{$product->product_attributes->first()['id']}}" class="btn btn-primary">to Cart</a>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}

                {{--@endforeach--}}
                {{--</div>--}}
            {{--@endforeach--}}
            {{--{{$products->appends(request()->query())->links()}}--}}
         {{--</div>--}}
@endsection
