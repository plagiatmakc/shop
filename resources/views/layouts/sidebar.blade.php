<div class="sidebar">

    <a href="#"><i class="fa fa-fw fa-home"></i> Dashboard</a>
    <a href="#"><i class="fa fa-fw fa-vcard"></i> Clients</a>
    <div class="dropdown show">
        <a href="#" class="btn dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-fw fa-wrench"></i>
            Products
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href="{{URL('products')}}">Index</a>
            <a class="dropdown-item" href="{{URL('products/create')}}">Create</a>
        </div>
    </div>
    <div class="dropdown show">
        <a href="#" class="btn dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-fw fa-envelope"></i>
            Categories
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item" href="{{URL('categories')}}">Index</a>
            <a class="dropdown-item" href="{{URL('categories/create')}}">Create</a>
        </div>
    </div>
    <a href="#"><i class="fa fa-fw fa-envelope"></i> Orders</a>
</div>
