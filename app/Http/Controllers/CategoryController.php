<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\Repositories\CategoryRepository;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\PaginationRequest;

class CategoryController extends Controller
{
    public function index(PaginationRequest $request)
    {
        $categories = new CategoryRepository;
        $response = $categories->allWithRelations();
        return response()->json($response);//view('layouts.category.index', ['categories' => $response]);
    }

    public function show($category, CategoryRepository $categoryRepository)
    {
        $response = $categoryRepository->getById($category);
        return view('layouts.category.show', ['category' => $response]);//response()->json($response);
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

    public function update($category, CategoryRequest $request, CategoryRepository $categoryRepository)
    {
        $categoryRepository->update($request->all(), $category);
        return $this->index();//response()->json($catrepo);
    }

    public function destroy($category, CategoryRepository $categoryRepository)
    {
       $categoryRepository->delete($category);
        return response()->json();//$this->index();//response()->json($catrepo);
    }

    public function edit($category, CategoryRepository $categoryRepository)
    {
        $result = $categoryRepository->getById($category);
        return view('layouts.category.edit', ['category' => $result]);
    }

    public function allCategories(CategoryRepository $categoryRepository)
    {
        return response()->json($categoryRepository->allCategories());
    }
}
