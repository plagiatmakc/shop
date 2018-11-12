<?php


namespace App\Repositories;

use Illuminate\Http\Request;
use App\ProductImage;
use App\Product;
use Illuminate\Support\Facades\Input;
use Exception;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class ImageRepository
{
    public function appendImagesToProduct($product_id, $request)
    {

        //return Request::hasFile($request['images']);
//        $images = Request::file('images');
        for ( $i=0; $i<count($request['images']);$i++) {
//            $uploadedFile=$image;
//            $uploadedFile->store('dummy');


//            $filename = $image->store('photos');
//            ProductsPhoto::create([
//                'product_id' => $product->id,
//                'filename' => $filename
//            ]);

            $originalFile = $request['images'][$i];
            $originalImage = Image::make($originalFile)->resize(600,600)->encode('png');
            $originalPath = 'images/products/' . $product_id . '/'. $i. '.png';
            $storeOriginal = Storage::put($originalPath , $originalImage->__toString());
            $url_full_image = Storage::url($originalPath);

            $thumbnailImage = Image::make($originalFile)->resize(150,150)->encode('png');
            $thumbnailPath = 'images/products/' . $product_id . '/thumbnail/'. $i . '.png';
            $storeThumb = Storage::put($thumbnailPath , $thumbnailImage->__toString());
            $url_thumb_image = Storage::url($thumbnailPath);

            try {
                ProductImage::create([
                    'product_id' => $product_id,
                    'link_to_file' =>  $url_full_image,
                    'link_to_thumb' =>  $url_thumb_image
                ]);
            }catch (Exception $e) {
                report($e);
                return report($e);
            }
            $originalImage->destroy();
            $thumbnailImage->destroy();
            unlink($originalFile);


        }
    }
}
