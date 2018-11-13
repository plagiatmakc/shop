<?php


namespace App\Repositories;

use Illuminate\Http\Request;
use App\ProductImage;
use App\Product;
use Illuminate\Support\Facades\Input;
use Exception;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class ProductImageRepository
{
    public function appendImagesToProduct($product_id, $request)
    {

        for ( $i=0; $i<count($request['images']);$i++) {

            $originalFile = $request['images'][$i];
            $originalImage = Image::make($originalFile)->resize(600,600)->encode($request['images'][$i]->extension());
            $originalPath = 'images/products/' . $product_id . '/'. $i. '.' . $request['images'][$i]->extension();
            $storeOriginal = Storage::put($originalPath , $originalImage->__toString());
//            $url_full_image = Storage::url($originalPath);

            $thumbnailImage = Image::make($originalFile)->resize(150,150)->encode($request['images'][$i]->extension());
            $thumbnailPath = 'images/products/' . $product_id . '/thumbnail/'. $i . '.' . $request['images'][$i]->extension();
            $storeThumb = Storage::put($thumbnailPath , $thumbnailImage->__toString());
//            $url_thumb_image = Storage::url($thumbnailPath);

            try {
                ProductImage::create([
                    'product_id' => $product_id,
                    'link_to_file' =>  $originalPath,
                    'link_to_thumb' =>  $thumbnailPath
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

    public function getImagesByProductId($product_id)
    {
        return ProductImage::where('product_id',$product_id)->get();
    }

    public function updateImage($image_id, $request)
    {
        $image = ProductImage::findOrFail($image_id);
        try{
            $originalFile = $request['image'];
            $originalImage = Image::make($originalFile)->resize(600,600)->encode('png');
            if (Storage::exists($image->link_to_file)) {
                Storage::delete($image->link_to_file);
            }
            Storage::put($image->link_to_file , $originalImage->__toString());
            $thumbnailImage = Image::make($originalFile)->resize(150,150)->encode('png');
            if (Storage::exists($image->link_to_thumb)) {
                Storage::delete($image->link_to_thumb);
            }
            Storage::put($image->link_to_thumb , $thumbnailImage->__toString());
        }catch (Exception $e) {
            report($e);
            return false;
        }

        return true;
    }

    public function deleteImage($image_id)
    {
        $image = ProductImage::findOrFail($image_id);
       try{
           if (Storage::exists($image->link_to_file)) {
               Storage::delete($image->link_to_file);
           }
           if (Storage::exists($image->link_to_thumb)) {
               Storage::delete($image->link_to_thumb);
           }
           $image->delete();
       }catch (Exception $e) {
           report($e);
           return false;
       }


    }

    public function deleteImagesByProduct($product_id)
    {
        $images = ProductImage::where('product_id', $product_id)->get();
        foreach ($images as $image )
        {
            $this->deleteImage($image->id);
        }
        if(Storage::exists('images/products/'.$product_id))
        {
            Storage::deleteDirectory('images/products/'.$product_id);
        }
    }


}
