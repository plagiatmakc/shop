<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CategoryRepository;
use App\Http\Requests\CategoryRequest;


class CategoryController extends Controller
{
    public function index(Request $request, CategoryRepository $categoryRepository)
    {
        if($request->ajax())
        {
         return response()
             ->json($categoryRepository->allWithRelations());
        }
    }

    public function show($category, CategoryRepository $categoryRepository, Request $request)
    {
        if($request->ajax()) {
            return response()
                ->json($categoryRepository->getById($category));
        }
    }

    public function store(CategoryRequest $request, CategoryRepository $categoryRepository)
    {
        if($request->ajax()) {
            return response()
                ->json($categoryRepository
                    ->create($request->all())
                );
        }
    }

    public function edit($category, CategoryRepository $categoryRepository, Request $request)
    {
        if($request->ajax()) {
            return response()
                ->json($categoryRepository->getById($category));
        }
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
