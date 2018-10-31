@extends('layouts.app')
@extends('layouts.sidebar')

@section('content')


<div class="container">
    <div class="card" style="width: 18rem;">
        {{--<img class="card-img-top" src="..." alt="Card image cap">--}}
        <div class="card-body">
            <h5 class="card-title">{{$category->title}}</h5>
            <p class="card-text">{{$category->description}}</p>
            <a href="/categories/{{$category->id}}/edit" class="btn btn-primary">Edit</a>
        </div>
    </div>
</div>


@endsection
