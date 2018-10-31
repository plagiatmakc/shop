@extends('layouts.app')
@extends('layouts.sidebar')

@section('content')


<div class="container">
    <div class="card" style="width: 18rem;">
        {{--<img class="card-img-top" src="..." alt="Card image cap">--}}
        <div class="card-body">
            <h5 class="card-title">{{$product->name}}</h5>

            @foreach($product->categories as $category)
            <h6 class="card-subtitle mb-2 text-muted">{{$category->title}}</h6>
            @endforeach
            <p class="card-text">{{$product->price}}</p>

            <a href="/products/{{$product->id}}/edit" class="btn btn-primary">Edit</a>
            <a href="/products/{{$product->id}}/attributes" class="btn btn-warning">Attributes</a>
        </div>
    </div>
</div>


@endsection
