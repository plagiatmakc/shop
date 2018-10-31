<?php

namespace App\Repositories;

use App\Category;
use Illuminate\Http\Request;
use Exception;

class CategoryRepository
{

    public function allCategories()
    {   $categories = Category::all();
        return $categories;
    }

    public function allWithRelations($items = null)
    {
        if ($items) {
            return Category::with('categoriesRecursive')->whereNull('parent_id')->paginate($items);
        }

        return Category::with('categoriesRecursive')->whereNull('parent_id')->get();
    }

    public function getById($category)
    {
        return Category::findOrFail($category);
    }

    public function create($request)
    {
        try {
            Category::create($request);
        } catch (Exception $e) {
            report($e);
            return false;
        }

    }

    public function update($request, $category)
    {
        try {
            Category::findOrFail($category)->update($request);
        } catch (Exception $e) {
            report($e);
            return false;
        }

    }

    public function delete($id)
    {
        try {
            Category::findOrFail($id)->delete();
        } catch (Exception $e) {
            report($e);
            return false;
        }

    }
}
