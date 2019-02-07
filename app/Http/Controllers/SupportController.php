<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Pusher\Pusher;

class SupportController extends Controller
{
    protected $pusher;
    public function __construct()
    {
        $options = array(
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true
        );
        $this->pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

    }

    public function sendMessage(Request $request)
    {
        $channel = $request->channel;
        $data['message'] = $request->message;
        $data['avatar'] = $request->avatar;
        try{
            $this->pusher->trigger($channel, 'my-event', $data);
        } catch (\Pusher\PusherException $e) {

            return response()->json($e->getJsonBody());
        }
        return response('Successful',200);

    }

    public function sendInvite(Request $request)
    {
        $data['message'] = $request->message;
        $data['room_id'] = $request->room_id;
        $data['time'] = date("Y-m-d H:i:s");
        try{
            $this->pusher->trigger('my-channel','my-event', $data);
        } catch (\Pusher\PusherException $e) {
            return response()->json($e->getJsonBody());
        }
        return response('Succesfull',200);
    }
}
