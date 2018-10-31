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

    <form method="POST" action="/products">
        @csrf
        <p>Name</p>
        <input type="text" name="name"><br>
        <p>Price</p>
        <input type="text" name="price"><br>
        <p>Currency</p>
        <input type="text" name="currency"><br>
        <p>Category</p>
        <select multiple="multiple" class="form-control input-sm" name="categories[]" id="category" style="width: 250px">
            @foreach($categories as $category)
                <option value="{{$category->id}}">{{$category->title}}     </option>
            @endforeach
        </select>
        <input type="submit" value="create" class="btn btn-info">
    </form>
</div>



@endsection
