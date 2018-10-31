@extends('layouts.app')
@extends('layouts.sidebar')

@section('content')



    <div class="container">

        <a href="{{request()->fullUrlWithQuery(['pagination' => 3])}}">per 3</a>
        <a href="{{request()->fullUrlWithQuery(['pagination' => 5])}}">per 5</a>
        <a href="{{request()->fullUrlWithQuery(['pagination' => 10])}}">per 10</a><br>

        <a href="{{request()->fullUrlWithQuery(['type' => "usd"])}}">USD</a>
        <a href="{{request()->fullUrlWithQuery(['type' => "eur"])}}">EUR</a>
        <a href="{{request()->fullUrlWithQuery(['type' => "uah"])}}">UAH</a>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Name</th>
                <th scope="col">Price</th>
                <th scope="col">Currency</th>
                <th scope="col">Operations</th>
            </tr>
            </thead>
            <tbody>
        @foreach($products as $product)
          <tr>
              <td>{{$product->id}} </td>
              <td>{{$product->name}}</td>
              <td>{{$product->price}}</td>
              <td>{{$product->currency}}</td>
              <td>
                  <div>
                  <a class="btn btn-info" href="/products/{{$product->id}}">Show</a>
                  <a class="btn btn-warning" href="/products/{{$product->id}}/edit">Edit</a>
                  <a class="btn btn-outline-secondary" href="/products/{{$product->id}}/attributes">Attributes</a>
                  <button class="btn btn-danger del" url="/products/{{ $product->id }}" data-token="{{ csrf_token() }}">
                      Delete
                  </button>
                  </div>
              </td>

        @endforeach
            </tbody>
        </table>
        {{$products->appends(request()->query())->links()}}
    </div>



@endsection
