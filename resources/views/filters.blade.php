<div class="sidebar ">
    <div class="sidebar-header ">
        <h3>Filters</h3>
    </div>
    <div class="dropdown show">
        <a href="#" class="btn dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-fw fa-wrench"></i>
            Pagination
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item {{request()->query('pagination') == 3 ? 'active': '' }}" href="{{request()->fullUrlWithQuery(['pagination' => 3])}}">per 3</a>
            <a class="dropdown-item {{request()->query('pagination') == 5 ? 'active': '' }}" href="{{request()->fullUrlWithQuery(['pagination' => 5])}}">per 5</a>
            <a class="dropdown-item {{request()->query('pagination') == 10 ? 'active': '' }}" href="{{request()->fullUrlWithQuery(['pagination' => 10])}}">per 10</a><br>
        </div>
    </div>

    <div class="dropdown show">
        <a href="#" class="btn dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-fw fa-wrench"></i>
            Currency
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item {{request()->query('type') == "usd" ? 'active': '' }}" href="{{request()->fullUrlWithQuery(['type' => "usd"])}}">USD </a>
            <a class="dropdown-item {{request()->query('type') == "eur" ? 'active': '' }}" href="{{request()->fullUrlWithQuery(['type' => "eur"])}}">EUR</a>
            <a class="dropdown-item {{request()->query('type') == "uah" ? 'active': '' }}"  href="{{request()->fullUrlWithQuery(['type' => "uah"])}}">UAH</a><br>
        </div>
    </div>

    {{--categories section--}}

    <div class="dropdown show">
        <a href="#" class="btn dropdown-toggle" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-fw fa-wrench"></i>
            Categories
        </a>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            @foreach($categories as $category)
                <a class="dropdown-item {{request()->query('category') == $category->id ? 'active': '' }}" href="{{request()->fullUrlWithQuery(['category' => $category->id])}}">{{$category->title}}</a>
            @endforeach
        </div>
    </div>


</div>
