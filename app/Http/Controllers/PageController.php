<?php

namespace App\Http\Controllers;

use App\CartImplementation;
use App\Category;
use App\Facades\ProductsRepository;
use App\Product;
use App\ProductAttributes;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductAttributesRepository;
use Illuminate\Http\Request;
use App\Http\Requests\PaginationAndCurrencyRequest;


class PageController extends Controller
{
    public function index(Request $request, CategoryRepository $categoryRepository, CartImplementation $cart)
    {
//        $response = ProductsRepository::getProductsByCategory($request->category ?? "all", $request->pagination ?? 5);
//        if ($request->currency_type != null) {
//            $response = ProductsRepository::convert_to($request->currency_type, $response);
//        }
//
//        return view('welcome', [
//            'products' => $response,
//            'categories' => $categoryRepository->allWithRelations(),
//            'cart' => json_encode($cart)
//        ]);
        return view('welcome');
    }

    public function getLastProducts(PaginationAndCurrencyRequest $request, CategoryRepository $categoryRepository, CartImplementation $cart)
    {
        $response = ProductsRepository::getProductsByCategory($request->category ?? "all", $request->pagination ?? 5);
        if ($request->currency_type != null) {
            $response = ProductsRepository::convert_to($request->currency_type, $response);
        }
        return response($response);
    }

    public function showProduct($id, $sku)
    {

        $product = ProductsRepository::getById($id);
        $attr = $product->product_attributes()
            ->where('sku', $sku)
            ->first();

        //  dd($attr);
        return view('showProduct', ['product' => $product, 'attr' => $attr]);
    }

    public function compare($url1 = null, $url2 = null, $type_compare = 'string')
    {
        //properties
        $time_start = microtime(true);
        $txt1 = file_get_contents('https://www.ndtv.com/bengali/international-womens-day-2019-womanifesto-released-women-organisations-ahead-of-lok-sabha-polls-2004200?');

        $txt2 = file_get_contents('https://www.ndtv.com/bengali/mamata-demands-probe-into-rafale-files-theft-2004239?');

//        dd($txt1,$txt2);
//        $txt1 = '<a href="#" class="btn dropdown-toggle" role="button"
//                   id="dropdownMenuLink"  aria-haspopup="true" aria-expanded="false">
//                    Pagination
//                </a>';
//
//        $txt2 = '<a href="#" class="btn dropdown-toggle" id="dropdownMenuLink" role="button"
//                     aria-haspopup="true" data-browse="false" aria-expanded="false">
//                    Pagination
//                </a>';

        //start function
        //first check by md5 if length compares texts equals
        if (strlen($txt1) == strlen($txt2)) {
            if (md5($txt1) == md5($txt2)) {

                $time_end = microtime(true);
                $time = $time_end - $time_start;
                return response('Totally compared with md5, Time: ' . $time, 200);
            }
        }
        //prepare array with type compare depending
        if ($type_compare == 'word') {
            $arr1 = explode(" ", trim(preg_replace('/\s+/', ' ', $txt1)));
            $arr2 = explode(" ", trim(preg_replace('/\s+/', ' ', $txt2)));
        } elseif ($type_compare == 'string') {
            $arr1 = explode("\n", $txt1);
            $arr2 = explode("\n", $txt2);
        } else {
            return response('Type compare not found', 404);
        }

        $count_arr1 = count($arr1);
        $count_arr2 = count($arr2);
        $count_success = 0;
        $i = 0;
        $count_offsets = 0;
        $offsets_info = [];
        $reference = 'first';
        if ($count_arr1 >= $count_arr2) {
            $max_array = $arr1;
            $min_array = $arr2;
            $max_array_count = $count_arr1;
            $min_array_count = $count_arr2;
        } else {
            $max_array = $arr2;
            $min_array = $arr1;
            $max_array_count = $count_arr2;
            $min_array_count = $count_arr1;
            $reference = 'second';
        }
        //trigger if compare position same
        $same_position = false;

        $check_duplicate = [];
        $default_offset = $max_array_count - $min_array_count;

        for ($i; $i < $max_array_count; $i++) {
            if($i > $default_offset) {
                $stripped_word_from_arr1 = trim(preg_replace('/\s+/', ' ', $max_array[$i]));
                for ($j = -$default_offset; $j <= $default_offset; $j++) {
                    if (array_key_exists(($i + $j), $min_array) || array_key_exists(($i - $j), $min_array)  ) {
                        $stripped_word_from_arr2 = trim(preg_replace('/\s+/', ' ', $min_array[$i+$j]));

                        if (strcasecmp($stripped_word_from_arr1, $stripped_word_from_arr2) == 0) {
                           if(!in_array(($i+$j+1),$check_duplicate)){
                               $count_success++;
                               array_push($check_duplicate, ($i+$j+1));
//                            array_push($offsets_info, ($i+1) . " --> " . ($i+$j+1));
                               $offsets_info[$i+1] = $i+$j+1;
                               break;
                           }

                        }
                    }

                }
            }else {
                $stripped_word_from_arr1 = trim(preg_replace('/\s+/', ' ', $max_array[$i]));
                for ($j = 0; $j <= $default_offset; $j++) {
                    if (array_key_exists(($i + $j), $min_array) || array_key_exists(($i - $j), $min_array)  ) {
                        $stripped_word_from_arr2 = trim(preg_replace('/\s+/', ' ', $min_array[$i+$j]));

                        if (strcasecmp($stripped_word_from_arr1, $stripped_word_from_arr2) == 0) {
                            if(!in_array(($i+$j+1), $check_duplicate)){
                                $count_success++;
                                array_push($check_duplicate, ($i+$j+1));
//                            array_push($offsets_info, ($i+1) . " --> " . ($i+$j+1));
                                $offsets_info[$i+1] = $i+$j+1;
                                break;
                            }

                        }
                    }

                }
            }






//            if ($i == 0 || $same_position == true) {
//                $stripped_word_from_arr1 = trim(preg_replace('/\s+/', ' ', $max_array[$i]));
//                $stripped_word_from_arr2 = trim(preg_replace('/\s+/', ' ', $min_array[$i]));
//
//                if (strcasecmp($stripped_word_from_arr1, $stripped_word_from_arr2) == 0) {
//                    $count_success++;
//                    $i++;
//                    array_push($offsets_info, "$i == $i");
//                    $same_position = true;
//                } elseif (array_key_exists($i + 1, $min_array)) {
//                    $stripped_word_from_arr2 = trim(preg_replace('/\s+/', ' ', $min_array[$i + 1]));
//
//                    if (strcasecmp($stripped_word_from_arr1, $stripped_word_from_arr2) == 0) {
//                        $count_success++;
//                        $i++;
//                        $count_offsets++;
//                        array_push($offsets_info, $reference == 'first' ? "$i --> " . ($i+1) : "$i <-- " . ($i+1));
//                        $same_position = false;
//                    } else {
//                        $i++;
//                        $same_position = false;
//                    }
//                } else {
//                    return $percent_hits = $count_success * 100 / $count_arr1;
//                }
//            } else {
//                $stripped_word_from_arr1 = trim(preg_replace('/\s+/', ' ', $max_array[$i]));
//                $stripped_word_from_arr2 = trim(preg_replace('/\s+/', ' ', $min_array[$i - 1]));
//
//                if (strcasecmp($stripped_word_from_arr1, $stripped_word_from_arr2) == 0) {
//                    $count_success++;
//                    $i++;
//                    $count_offsets++;
//                    array_push($offsets_info, $reference == 'first' ? ($i-1) . " <-- $i" : ($i-1) . " --> $i"  );
//                } elseif (array_key_exists($i, $min_array)) {
//                    $stripped_word_from_arr2 = trim(preg_replace('/\s+/', ' ', $min_array[$i]));
//
//                    if (strcasecmp($stripped_word_from_arr1, $stripped_word_from_arr2) == 0) {
//                        $count_success++;
//                        $i++;
//                        array_push($offsets_info, "$i == $i");
////                        $same_position = true;
//                    } elseif (array_key_exists($i + 1, $min_array)) {
//                        $stripped_word_from_arr2 = trim(preg_replace('/\s+/', ' ', $min_array[$i + 1]));
//
//                        if (strcasecmp($stripped_word_from_arr1, $stripped_word_from_arr2) == 0) {
//                            $count_success++;
//                            $i++;
//                            $count_offsets++;
//                            array_push($offsets_info, $reference == 'first' ? "$i --> " . ($i+1) : "$i <-- " . ($i+1));
//                        } else {
//                            $i++;
//                        }
//                    } else {
//                        $i++;
//                    }
//                } else {
//                   break;
//                }
//
//            }

        }

        $percent_hits = $count_success * 100 / $count_arr1;

        $time_end = microtime(true);
        $time = $time_end - $time_start;

        return response()->json([
            'Hits percent' => $percent_hits . '%',
            'hits' => $count_success . ' of ' . $count_arr1,
            'Count offsets' => $count_offsets, 'Time' => $time,
            'offsets info' => $offsets_info,
            'type compare' => $type_compare,
            'reference' => $reference
        ]);
    }


}
