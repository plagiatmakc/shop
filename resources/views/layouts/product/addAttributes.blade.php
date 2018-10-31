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
        <div class="card" style="width: 18rem;">
            {{--<img class="card-img-top" src="..." alt="Card image cap">--}}
            <div class="card-body">
                <h5 class="card-title">{{$product->name}}</h5>

                @foreach($product->categories as $category)
                    <h6 class="card-subtitle mb-2 text-muted">{{$category->title}}</h6>
                @endforeach
                <p class="card-text">{{$product->price}}</p>

                <a href="/products/{{$product->id}}/edit" class="btn btn-primary ">Edit</a>
            </div>
        </div>

        <table id="attributes" class="table table-striped table-hover ">
            <thead>
            <tr>
                <th scope="col">SKU</th>
                <th scope="col">Size</th>
                <th scope="col">Price</th>
                <th scope="col">Stock</th>
                <th scope="col">Operations</th>
            </tr>
            </thead>
            <tbody>

            @foreach($product->product_attributes as $attribute)
                <tr id="{{$attribute->id}}">
                    <td>{{$attribute->sku}} </td>
                    <td>{{$attribute->size}}</td>
                    <td>{{$attribute->price}}</td>
                    <td>{{$attribute->stock}}</td>
                    <td>
                        <div>
                            {{--<a class="btn btn-warning" href="/products/#/edit_attr">Edit</a>--}}
                            <button type="button" class="btn btn-primary editAttr" data-toggle="modal" data-target="#exampleModalCenter">
                                Edit
                            </button>
                            <button class="btn btn-danger del" url="/attributes/{{$attribute->id}}" data-token="{{ csrf_token() }}" >
                                Delete
                            </button>
                        </div>
                    </td>


            @endforeach
            </tbody>
        </table>


        <form method="POST" action="/products/{{$product->id}}/attributes">
            @csrf
            <input type="hidden" name="product_id" value="{{$product->id}}"><br>
            <input type="text" name="sku" placeholder="SKU">
            <input type="text" name="size" placeholder="Size">
            <input type="text" name="price" placeholder="Price">
            <input type="text" name="stock" placeholder="Stock">
            <input type="submit" value="create" class="btn btn-info">
        </form>

            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Change Attribute</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                                <input id="id_prod" type="hidden" name="product_id" value="{{$product->id}}">
                                <input id="id_attr" type="hidden" name="id" title="id" value="">
                                <input id="id_sku" type="text" name="sku" title="SKU" value="">
                                <input id="id_size" type="text" name="size" title="Size" value="">
                                <input id="id_price" type="text" name="price" title="Price" value="">
                                <input id="id_stock" type="text" name="stock" title="Stock" value="">
                            <div class="modal-footer">
                                <button id="change_attr" url="" class="btn btn-info changeAttr" data-token="{{ csrf_token() }}" data-dismiss="modal">Update</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </div>

@endsection
