<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CategoryRepository;
use App\Http\Requests\CategoryRequest;


class CategoryController extends Controller
{
    /*
   |--------------------------------------------------------------------------
   | Category Controller
   |--------------------------------------------------------------------------
   |
   | This controller handles CRUD categories for the application. The controller has a Swagger
   | annotations to conveniently provide functionality with swagger-ui in this application.
   |
   */
    /**
     * @SWG\Get(
     *     path="/categories",
     *     summary="Get list of categories with relations",
     *     tags={"Categories"},
     *     @SWG\Parameter(
     *     name="X-Requested-With",
     *     in="header",
     *     required=true,
     *     type="string",
     *     default="XMLHttpRequest",
     *      ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="object",
     *             allOf={
     *                 @SWG\Schema(ref="#/definitions/Category"),
     *                 @SWG\Schema(
     *                      type="object",
     *                      @SWG\Property(
     *                          property="categories_recursive",
     *                          type="array",
     *                          description="Array of subcategories",
     *                          @SWG\Items(
     *
     *                          ),
     *                      ),
     *                 ),
     *
     *             }
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Unauthorized user",
     *     ),
     * )
     */
    /**
     * Get all categories with relations.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Repositories\CategoryRepository $categoryRepository
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, CategoryRepository $categoryRepository)
    {
        if ($request->ajax()) {
            return response()
                ->json($categoryRepository->allWithRelations());
        }
    }

    /**
     * @SWG\Get(
     *     path="/categories/{category}",
     *     summary="Get category by id with relations",
     *     tags={"Categories"},
     *     description="Get category by id",
     *     @SWG\Parameter(
     *         name="category",
     *         in="path",
     *         description="Category id",
     *         required=true,
     *         type="integer",
     *         minimum=1,
     *     ),
     *     @SWG\Parameter(
     *         name="X-Requested-With",
     *         in="header",
     *         required=true,
     *         type="string",
     *         default="XMLHttpRequest",
     *      ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="object",
     *             allOf={
     *                 @SWG\Schema(ref="#/definitions/Category"),
     *                 @SWG\Schema(
     *                     type="object",
     *                     @SWG\Property(
     *                          property="categories_recursive",
     *                          type="array",
     *                          @SWG\Items()
     *                     ),
     *                 ),
     *             }
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Unauthorized user",
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Category is not found",
     *     )
     * )
     */
    /**
     * Get category with relations.
     *
     * @param  integer $category
     * @param  \App\Repositories\CategoryRepository $categoryRepository
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function show($category, CategoryRepository $categoryRepository, Request $request)
    {
        if ($request->ajax()) {
            return response()
                ->json($categoryRepository->getById($category));
        }
    }

    /**
     *
     * @SWG\Post(
     *        path="/categories",
     *        tags={"Categories"},
     *        operationId="createCategory",
     *        summary="Create category",
     * 		@SWG\Parameter(
     *            name="X-Requested-With",
     *            in="header",
     *            required=true,
     *          type="string",
     *          default="XMLHttpRequest"
     *        ),
     * 		@SWG\Parameter(
     *            name="title",
     *            in="formData",
     *            required=true,
     *          type="string",
     *        ),
     * 		@SWG\Parameter(
     *            name="description",
     *            in="formData",
     *            required=true,
     *          type="string",
     *        ),
     * 		@SWG\Parameter(
     *            name="parent_id",
     *            in="formData",
     *            required=false,
     *          type="integer",
     *        ),
     * 		@SWG\Response(
     *            response="200",
     *            description="Success",
     *          @SWG\Schema(
     *              ref="#/definitions/Category",
     *          ),
     *        ),
     * 		@SWG\Response(
     *         response="401",
     *         description="Unauthorized user",
     *     ),
     * 		@SWG\Response(
     *         response="422",
     *         description="Unprocessable Entity",
     *     ),
     *    )
     *
     */
    /**
     * Create category with relations.
     *
     * @param  \App\Http\Requests\CategoryRequest $request
     * @param  \App\Repositories\CategoryRepository $categoryRepository
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request, CategoryRepository $categoryRepository)
    {
        if ($request->ajax()) {
            return response()
                ->json($categoryRepository
                    ->create($request->all())
                );
        }
    }

    /**
     * Edit category with relations.
     *
     * @param  integer $category
     * @param  \App\Http\Requests\CategoryRequest $request
     * @param  \App\Repositories\CategoryRepository $categoryRepository
     * @return \Illuminate\Http\Response
     */
    public function edit($category, CategoryRepository $categoryRepository, Request $request)
    {
        if ($request->ajax()) {
            return response()
                ->json($categoryRepository->getById($category));
        }
    }

    /**
     *
     * @SWG\Put(
     *        path="/categories/{category}",
     *        tags={"Categories"},
     *        operationId="updateCategory",
     *        summary="Update category",
     * 		@SWG\Parameter(
     *            name="category",
     *            in="path",
     *            required=true,
     *            type="integer",
     *            description="ID of category",
     *        ),
     * 		@SWG\Parameter(
     *            name="title",
     *            in="formData",
     *            required=true,
     *          type="string",
     *        ),
     * 		@SWG\Parameter(
     *            name="id",
     *            in="formData",
     *            required=true,
     *          type="integer",
     *          description="Need to validate"
     *        ),
     * 		@SWG\Parameter(
     *            name="description",
     *            in="formData",
     *            required=true,
     *          type="string",
     *        ),
     * 		@SWG\Parameter(
     *            name="parent_id",
     *            in="formData",
     *            required=false,
     *          type="integer",
     *        ),
     * 		@SWG\Response(
     *            response="200",
     *            description="Success",
     *        ),
     * 		@SWG\Response(
     *         response="401",
     *         description="Unauthorized user",
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Category is not found",
     *     ),
     *     @SWG\Response(
     *         response="422",
     *         description="Unprocessable Entity",
     *     )
     *    )
     *
     */
    /**
     * Update category.
     *
     * @param  integer $category
     * @param  \App\Http\Requests\CategoryRequest $request
     * @param  \App\Repositories\CategoryRepository $categoryRepository
     * @return \Illuminate\Http\Response
     */
    public function update($category, CategoryRequest $request, CategoryRepository $categoryRepository)
    {
        return response()
            ->json($categoryRepository
                ->update($request->all(), $category)
            );
    }

    /**
     *
     * @SWG\Delete(
     *        path="/categories/{id}",
     *        tags={"Categories"},
     *        operationId="deleteCategory",
     *        summary="Remove category",
     * 		@SWG\Parameter(
     *            name="id",
     *            in="path",
     *            required=true,
     *            type="integer",
     *            description="id number of category",
     *        ),
     * 		@SWG\Response(
     *            response=200,
     *            description="Success",
     *        ),
     * 		@SWG\Response(
     *            response="404",
     *          description="Category is not found",
     *        ),
     *    )
     *
     */
    /**
     * Delete category with relations.
     *
     * @param  integer $category
     * @param  \App\Repositories\CategoryRepository $categoryRepository
     * @return \Illuminate\Http\Response
     */
    public function destroy($category, CategoryRepository $categoryRepository)
    {

        return response()
            ->json($categoryRepository
                ->delete($category)
            );
    }

    /**
     * @SWG\Get(
     *     path="/all-categories",
     *     summary="Get all categories",
     *     tags={"Categories"},
     *     description="Get categories",
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *        @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Category")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Unauthorized user",
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Post is not found",
     *     )
     * )
     */
    /**
     * Get list of category without relations.
     *
     * @param  \App\Repositories\CategoryRepository $categoryRepository
     * @return \Illuminate\Http\Response
     */
    public function allCategories(CategoryRepository $categoryRepository)
    {
        return response()
            ->json($categoryRepository
                ->allCategories()
            );
    }
}
