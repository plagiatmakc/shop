@extends('layouts.app')
@extends('layouts.sidebar')

@section('content')


    <div class="container">


    <div class="panel panel-primary">

        <div class="panel-heading"></div>

        <div class="panel-body">

            <div class="row">

                <div class="col-md-6">

                    <h3>Category List</h3>

                    <ul id="tree1">

                        @foreach($categories as $category)

                            <li>

                                {{ $category->title }}

                                @if(count($category->categories))

                                    @include('layouts.category.manageChild',['categories' => $category->categories])

                                @endif

                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

    </div>

    </div>



@endsection
