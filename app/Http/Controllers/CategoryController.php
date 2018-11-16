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
         return response()->json($response);//view('layouts.category.index', ['categories' => $response]);
        }

    }

    public function show($category, CategoryRepository $categoryRepository)
    {
        $response = $categoryRepository->getById($category);
        return response()->json($response);
    }

    public function create()
    {
        return view('layouts.category.create');
    }

    public function store(CategoryRequest $request, CategoryRepository $categoryRepository)
    {
        $result = $categoryRepository->create($request->all());
        return response()->json($result);//view('layouts.category.create');
    }

    public function edit($category, CategoryRepository $categoryRepository)
    {
        $category_for_edit = $categoryRepository->getById($category);
        return response()->json($category_for_edit);//view('layouts.category.edit', ['category' => $result]);
    }

    public function update($category, CategoryRequest $request, CategoryRepository $categoryRepository)
    {
        $result = $categoryRepository->update($request->all(), $category);
        return response()->json($result);
    }

    public function destroy($category, CategoryRepository $categoryRepository)
    {
        $result = $categoryRepository->delete($category);
        return response()->json($result);
    }

    public function allCategories(CategoryRepository $categoryRepository)
    {
        return response()->json($categoryRepository->allCategories());
    }
}
