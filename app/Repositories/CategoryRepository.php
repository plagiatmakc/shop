<?php

namespace App\Repositories;

use App\Category;
use Illuminate\Http\Request;
use Exception;

class CategoryRepository
{

    public function allCategories()
    {
        return Category::all();
    }

    public function allWithRelations($items = null)
    {
        if ($items) {
            return Category::with('categoriesRecursive')
                ->whereNull('parent_id')
                ->paginate($items);
        }

        return Category::with('categoriesRecursive')
            ->whereNull('parent_id')
            ->get();
    }

    public function getById($category)
    {
        return Category::with('categoriesRecursive')->findOrFail($category);
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
            if (Category::findOrFail($id)->categories) {
                $result = $this->deleteRecursive($id);
            } else {
                $result = Category::findOrFail($id)->delete();
            }
            return $result;

        } catch (Exception $e) {
            report($e);
            return false;
        }

    }

    public function deleteRecursive($id)
    {
        if (Category::findOrFail($id)->categories) {
            $sub_categories = Category::where('parent_id', $id)->get();
            foreach ($sub_categories as $sub_category) {
                if ($sub_category->categories) {
                    $this->deleteRecursive($sub_category->id);
                } else {
                    try {
                        $result = Category::findOrFail($sub_category->id)->delete();
                        return $result;
                    } catch (Exception $e) {
                        report($e);
                        return false;
                    }
                }
            }
        }
        try {
            $result = Category::findOrFail($id)->delete();
            return $result;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }
}
