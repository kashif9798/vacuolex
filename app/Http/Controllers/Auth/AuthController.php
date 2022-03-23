<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Auth;

class AuthController extends ApiController
{
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::guard('sanctum')->user();
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
