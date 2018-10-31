<ul>

    @foreach($categories as $category)

        <li>

            {{ $category->title }}

            @if(count($category->categories))

                @include('layouts.category.manageChild',['categories' => $category->categories])

            @endif

        </li>

    @endforeach

</ul>
