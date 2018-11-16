<?php

namespace App\Http\Controllers;

use App\Repositories\ProductImageRepository;
use Illuminate\Http\Request;
use App\Http\Requests\ImageRequest;
use DateTime;

class ProductImagesController extends Controller
{

    public function index(Request $request)
    {
       if($request->has('product_id'))
       {
           $imageRepository = new ProductImageRepository();

           return response($imageRepository->getImagesByProductId($request->product_id));
       }
    }

    public function update($product_image, ImageRequest $request)
    {
        if($request->hasFile('image')){
            $imageRepository = new ProductImageRepository();
            $result = $imageRepository->updateImage($product_image, $request->all());

            return response()->json($result);
        }

        return response()->json();

    }

    public function destroy($product_image)
    {
        $imageRepository = new ProductImageRepository();

        return response()->json($imageRepository->deleteImage($product_image));
    }

    public function store(ImageRequest $request)
    {

        if($request->has('product_id') && $request->hasFile('images'))
        {
            $imageRepository = new ProductImageRepository();
            return response($imageRepository->appendImagesToProduct($request->product_id, $request->all()));
        }

    }

}
