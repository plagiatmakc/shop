<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Stripe\Stripe;
use \Stripe\Token;
use \Stripe\Charge;
use App\Order;

class StripeController extends Controller
{
    //
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

    }

    public function charge(Request $request)
    {
        try {
            // Use Stripe's library to make requests...
            //Need Stripe license for creating token on your server(because card info)
            //Another way create token from Stripe.js and make charge with recive token from response
//            $token = Token::create([
//                "card" => [
//                    "number" => $request->card['card_number'],
//                    "exp_month" => $request->card['expiry_month'],
//                    "exp_year" => $request->card['expiry_year'],
//                    "cvc" => $request->card['cvv']
//                ]
//            ]);

           $charge = Charge::create([
                "amount" => $request->amount * 100,
                "currency" => "usd",
                "source" => $request->source_id, // obtained with Stripe.js
                "description" => $request->description,
                "receipt_email" => $request->email
            ]);

        } catch(\Stripe\Error\Card $e) {
            // Since it's a decline, \Stripe\Error\Card will be caught
            return response()->json($e->getJsonBody());
        } catch (\Stripe\Error\RateLimit $e) {
            // Too many requests made to the API too quickly
            return response()->json($e->getJsonBody());
        } catch (\Stripe\Error\InvalidRequest $e) {
            // Invalid parameters were supplied to Stripe's API
            return response()->json($e->getJsonBody());
        } catch (\Stripe\Error\Authentication $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            return response()->json($e->getJsonBody());
        } catch (\Stripe\Error\ApiConnection $e) {
            // Network communication with Stripe failed
            return response()->json($e->getJsonBody());
        } catch (\Stripe\Error\Base $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            return response()->json($e->getJsonBody());
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            return response()->json($e->getJsonBody());
        }

        return response()->json($charge);

    }

}
