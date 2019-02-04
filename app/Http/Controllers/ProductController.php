<?php

namespace App\Http\Controllers;

use App\Category;
use App\Facades\ProductsRepository;
use App\Http\Requests\PaginationAndCurrencyRequest;
use App\Product;
use App\ProductAttributes;
use App\Repositories\ProductImageRepository;
use App\Repositories\ProductAttributesRepository;
use Illuminate\Http\Request;
use App\Repositories\ProductRepository;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductAttributeRequest;


class ProductController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/products",
     *     summary="Get list of products with images and pagination",
     *     tags={"Products"},
     *     @SWG\Parameter(
     *     name="pagination",
     *     in="query",
     *     required=false,
     *     type="integer",
     *     minimum=1
     *      ),
     *     @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     required=false,
     *     type="integer",
     *     minimum=1
     *      ),
     *     @SWG\Parameter(
     *     name="currency_type",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"usd", "eur", "uah"}
     *      ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(
     *                  property="current_page",
     *                  type="integer"
     *             ),
     *             @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(
     *                      allOf={
     *                          @SWG\Schema(ref="#/definitions/Product"),
     *                          @SWG\Schema(
     *                              type="object",
     *                              @SWG\Property(
     *                                  property="product_images",
     *                                  type="array",
     *                                  @SWG\Items(ref="#/definitions/ProductImage"),
     *                              ),
     *                          ),
     *                      }
     *                  ),
     *             ),
     *             @SWG\Property(
     *                  property="first_page_url",
     *                  type="string"
     *             ),
     *             @SWG\Property(
     *                  property="from",
     *                  type="integer"
     *             ),
     *             @SWG\Property(
     *                  property="last_page",
     *                  type="integer"
     *             ),
     *             @SWG\Property(
     *                  property="last_page_url",
     *                  type="string"
     *             ),
     *             @SWG\Property(
     *                  property="next_page_url",
     *                  type="string"
     *             ),
     *             @SWG\Property(
     *                  property="path",
     *                  type="string"
     *             ),
     *             @SWG\Property(
     *                  property="per_page",
     *                  type="integer"
     *             ),
     *             @SWG\Property(
     *                  property="prev_page_url",
     *                  type="string"
     *             ),
     *             @SWG\Property(
     *                  property="to",
     *                  type="integer"
     *             ),
     *             @SWG\Property(
     *                  property="total",
     *                  type="integer"
     *             ),
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Unauthorized user",
     *     ),
     * )
     */
    public function index(PaginationAndCurrencyRequest $request)
    {
        $products = ProductsRepository::getProducts($request->pagination);
        if ($request->currency_type != null) {
            $products = ProductsRepository::convert_to($request->currency_type, $products);
        }
        return response($products);//view('layouts.product.index',['products' => $response]);
    }

    /**
     * @SWG\Get(
     *     path="/products/{product}",
     *     summary="Get list of products with images and pagination",
     *     tags={"Products"},
     *     @SWG\Parameter(
     *         name="product",
     *         in="path",
     *         description="Product ID",
     *         required=true,
     *         type="integer",
     *         minimum=1
     *     ),
     *     @SWG\Parameter(
     *         name="currency_type",
     *         in="query",
     *         required=false,
     *         type="string",
     *         enum={"usd", "eur", "uah"}
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="object",
     *             allOf={
     *                  @SWG\Schema(ref="#/definitions/Product"),
     *                  @SWG\Schema(
     *                      type="object",
     *                      @SWG\Property(
     *                          property="categories",
     *                          type="array",
     *                          @SWG\Items(
     *                              allOf={
     *                                  @SWG\Schema(ref="#/definitions/Category"),
     *                                  @SWG\Schema(
     *                                      type="object",
     *                                      @SWG\Property(
     *                                          property="pivot",
     *                                          type="object",
     *                                          @SWG\Property(property="product_id", type="integer"),
     *                                          @SWG\Property(property="category_id", type="integer"),
     *                                      ),
     *                                  ),
     *                              }
     *                          ),
     *                      ),
     *                      @SWG\Property(
     *                          property="product_images",
     *                          type="array",
     *                          @SWG\Items(ref="#/definitions/ProductImage"),
     *                      ),
     *                  ),
     *             }
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Unauthorized user",
     *     ),
     * )
     */
    public function show($product,PaginationAndCurrencyRequest $request)
    {
        $product_info = ProductsRepository::getById($product);
        if ($request->currency_type != null) {
            $product_info = ProductsRepository::single_convert_to($request->currency_type, $product_info);
        }
        return response()->json($product_info);//view('layouts.product.show', ['product' => $response])
    }

    public function create()
    {
        $categories = Category::all();
        return view('layouts.product.create', ['categories' => $categories]);
    }

    /**
     *
     * 	@SWG\Post(
     * 		path="/products",
     * 		tags={"Products"},
     * 		operationId="createProduct",
     * 		summary="Create product",
     *      consumes={"multipart/form-data"},
     * 		@SWG\Parameter(
     * 			name="name",
     * 			in="formData",
     * 			required=true,
     *          type="string",
     *		),
     * 		@SWG\Parameter(
     * 			name="price",
     * 			in="formData",
     * 			required=true,
     *          type="number",
     *		),
     * 		@SWG\Parameter(
     * 			name="currency",
     * 			in="formData",
     * 			required=true,
     *          type="string",
     *          enum={"usd","eur","uah"}
     *		),
     * 		@SWG\Parameter(
     * 			name="images[0]",
     * 			in="formData",
     * 			required=false,
     *          description="request can attempt array images[i]:{file} /mimes:jpeg,bmp,png/max size:2Mb",
     *          type="file",
     *		),
     * 		@SWG\Parameter(
     * 			name="images[1]",
     * 			in="formData",
     * 			required=false,
     *          description="this example additing more images",
     *          type="file",
     *		),
     * 		@SWG\Parameter(
     * 			name="categories[0]",
     * 			in="formData",
     * 			required=false,
     *          type="integer",
     *		),
     * 		@SWG\Response(
     * 			response="200",
     * 			description="Success",
     *          @SWG\Schema(
     *              ref="#/definitions/Product",
     *          ),
     * 		),
     * 		@SWG\Response(
     *         response="401",
     *         description="Unauthorized user",
     *     ),
     * 		@SWG\Response(
     *         response="422",
     *         description="Unprocessable Entity",
     *     ),
     * 	)
     *
     */
    public function store(ProductRequest $request)
    {
        $product = ProductsRepository::createRecord($request->all());
        if($request->hasFile('images')) {
            $imageRepository = new ProductImageRepository();
            $imageRepository->appendImagesToProduct($product->id, $request);
        }
        if($request->has('categories')) {
            $product->categories()
                ->attach($request->categories);
        }
        return response()->json($product);//redirect('/products');
    }

    public function edit($product)
    {
        return response()->json(ProductsRepository::getById($product));//view('layouts.product.edit',['product' => $result, 'categories' => $categories]);
    }

    /**
     *
     * 	@SWG\Put(
     * 		path="/products/{product}",
     * 		tags={"Products"},
     * 		operationId="updateProduct",
     * 		summary="Update product",
     * 		@SWG\Parameter(
     * 			name="product",
     * 			in="path",
     * 			required=true,
     * 			type="integer",
     * 			description="ID of product",
     * 		),
     * 		@SWG\Parameter(
     * 			name="id",
     * 			in="formData",
     * 			required=true,
     *          type="integer",
     *          description="Need to validate for update"
     *		),
     * 		@SWG\Parameter(
     * 			name="name",
     * 			in="formData",
     * 			required=true,
     *          type="string",
     *		),
     * 		@SWG\Parameter(
     * 			name="price",
     * 			in="formData",
     * 			required=true,
     *          type="number",
     *		),
     * 		@SWG\Parameter(
     * 			name="currency",
     * 			in="formData",
     * 			required=true,
     *          type="string",
     *          enum={"usd", "eur", "uah"},
     *		),
     * 		@SWG\Parameter(
     * 			name="categories[0]",
     * 			in="formData",
     * 			required=false,
     *          type="integer",
     *		),
     * 		@SWG\Response(
     * 			response="200",
     * 			description="Success",
     *          @SWG\Schema(
     *              ref="#/definitions/Product",
     *          ),
     * 		),
     * 		@SWG\Response(
     *         response="401",
     *         description="Unauthorized user",
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Product is not found",
     *     ),
     *     @SWG\Response(
     *         response="422",
     *         description="Unprocessable Entity",
     *     )
     * 	)
     *
     */
    public function update(ProductRequest $request, $product)
    {
        $result = ProductsRepository::updateRecord($request->all(), $product);
        return response()->json($result);//redirect('/products')
    }

    /**
     *
     * 	@SWG\Delete(
     * 		path="/products/{product}",
     * 		tags={"Products"},
     * 		operationId="deleteProduct",
     * 		summary="Remove product",
     * 		@SWG\Parameter(
     * 			name="product",
     * 			in="path",
     * 			required=true,
     * 			type="integer",
     * 			description="id number of product",
     * 		),
     * 		@SWG\Response(
     * 			response=200,
     * 			description="Success",
     * 		),
     * 		@SWG\Response(
     * 			response="404",
     *          description="Product is not found",
     * 		),
     * 	)
     *
     */
    public function destroy($product)
    {
        return ProductsRepository::delete($product);
    }

//    public function addAttr($product_id)
//    {
//        $product = ProductsRepository::getById($product_id);
//        return view('layouts.product.addAttributes', ['product' => $product,]);
//    }
//
//    public function storeAttr(ProductAttributeRequest $request, ProductAttributesRepository $attributesRepository)
//    {
//        $attributesRepository->create($request->all());
//        return redirect()->back();//response()->json($product);
//    }
//
//    public function delAttr($id, ProductAttributesRepository $attributesRepository)
//    {
//        $attributesRepository->delete($id);
//        return redirect()->back();
//    }
//
//    public function editAttr($id, ProductAttributesRepository $attributesRepository)
//    {
//        $result = $attributesRepository->getById($id);
//        return response()->json($result);
//    }
//
//    public function changeAttr($id, ProductAttributeRequest $request, ProductAttributesRepository $attributesRepository)
//    {
//        $result = $attributesRepository->update($id, $request->all());
//        return response()->json($result);
//    }
}
