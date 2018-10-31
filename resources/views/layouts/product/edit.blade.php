@extends('layouts.app')
@extends('layouts.sidebar')

@section('content')

    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/products/{{$product->id}}">
            @method('put')
            @csrf
            <p>Name</p>
            <input type="text" name="name" value="{{$product->name}}"><br>
            <p>Price</p>
            <input type="text" name="price" value="{{$product->price}}"><br>
            <p>Currency</p>
            <input type="text" name="currency" value="{{$product->currency}}"><br>
            <p>Category</p>

            <select multiple="multiple" class="form-control input-sm" name="categories[]"  id="category" style="width: 250px">
                @foreach($categories as $category)
                    <option value="{{$category->id}}" @if($product->categories->groupBy('id')->has($category->id))selected="selected"@endif>{{$category->title}}</option>
                @endforeach
            </select>

            <input type="submit" value="update" class="btn btn-info">
        </form>
    </div>



@endsection
