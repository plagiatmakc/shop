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

        <form method="POST" action="/categories/{{$category->id}}" >
            @method('put')
            @csrf
            <p>Title</p>
            <input type="text" name="title" value="{{$category->title}}"><br>
            <p>Description</p>
            <input type="text" name="description" value="{{$category->description}}"><br>
            <input type="submit" value="update" class="btn btn-info">
        </form>
    </div>



@endsection
