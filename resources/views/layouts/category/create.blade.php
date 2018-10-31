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

    <form method="POST" action="/categories">
        @csrf
        <p>Title</p>
        <input type="text" name="title"><br>
        <p>Description</p>
        <input type="text" name="description"><br>
        <input type="submit" value="create" class="btn btn-info">
    </form>
</div>



@endsection
