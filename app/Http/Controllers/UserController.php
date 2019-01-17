<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\User;
use App\Role;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::all());
    }

    public function login(Request $request)
    {
        $status = 401;
        $response = ['error' => 'Unauthorised'];

        if (Auth::attempt($request->only(['email', 'password']))) {
            $status = 200;
            $response = [
                'user' => Auth::user(),
                'token' => Auth::user()->createToken('bigStore')->accessToken,
                'role' => Auth::user()->hasRole('Admin')? Auth::user()->roles->first() : 0 ,
            ];
        }

        return response()->json($response, $status);
    }

    public function register(RegisterRequest $request)
    {

        $data = $request->only(['first_name', 'last_name', 'phone', 'email', 'password']);
        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        if($request->hasFile('avatar'))
        {
            $originalFile = $request['avatar'];
            $thumbnailImage = Image::make($originalFile)->resize(50,50)->encode($request['avatar']->extension());
            $thumbnailPath = 'images/avatars/' . $user->id . '/' . time() . '.' . $request['avatar']->extension();
            $storeThumb = Storage::put($thumbnailPath , $thumbnailImage->__toString());
            $user->update(['avatar' => $thumbnailPath]);
        }

        $role = Role::where('name','=','User')->first();
        $user->roles()->attach($role);

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('bigStore')->accessToken,
            'role' => 0,
        ]);
    }

    public function show(User $user)
    {
        return response()->json($user);
    }

    public function showOrders(User $user)
    {
        return response()->json($user->orders()->get());
    }

}