<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\Repositories\CategoryRepository;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\PaginationAndCurrencyRequest;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax())
        {
         $categories = new CategoryRepository;
         $response = $categories->allWithRelations();
         return response()->json($response);
        }

    }

    public function show($category, CategoryRepository $categoryRepository)
    {
        return response()
            ->json($categoryRepository->getById($category));
    }

    public function store(CategoryRequest $request, CategoryRepository $categoryRepository)
    {
        ;
        return response()
            ->json($categoryRepository
                ->create($request->all())
            );
    }

    public function edit($category, CategoryRepository $categoryRepository)
    {
        return response()
            ->json($categoryRepository->getById($category));
    }

    public function update($category, CategoryRequest $request, CategoryRepository $categoryRepository)
    {
        return response()
            ->json($categoryRepository
                ->update($request->all(), $category)
            );
    }

    public function destroy($category, CategoryRepository $categoryRepository)
    {

        return response()
            ->json($categoryRepository
                ->delete($category)
            );
    }

    public function allCategories(CategoryRepository $categoryRepository)
    {
        return response()
            ->json($categoryRepository
                ->allCategories()
            );
    }
}
