<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ApiController;

class AuthController extends ApiController
{
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
 
        if ($user && Hash::check($request->password, $user->password)) {
            // $user = Auth::guard('sanctum')->user();
            $body['token'] = $user->createToken('myApp')->plainTextToken;
            $body['user'] = $user;

            return $this->showMessage($body);
        }
        else{
            return $this->errorResponse('Email/Password incorrect', 401);
        }
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validate($request,[
            "name"      => ["required", "string", "max:255"],
            "email"     => ["required", "string", "email", "unique:users"],
            "password"  => ["required", "string", "max:255"]
        ]);

        $user = User::create([
            "name"      => $request->name,
            "email"     => $request->email,
            "password"  => bcrypt($request->password)
        ]);

        return $this->login($request);
    }
}
