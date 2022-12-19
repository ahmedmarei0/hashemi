<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
     //   $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' =>"required|string",
            'email' =>"required|string|unique:users|email",
            'username' =>"required|string|unique:users",
            'password' =>"required|string",
        ]);
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;
        return response([
            'user'=>$user,
            'token' => $token,
        ], 201);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' =>"required|string|email",
            'password' =>"required|string",
        ]);
        $user = \App\Models\User::where('email', $request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password)){
            return response([
                'message' => "Bad Creds"
            ] , 401);
        }
        $token = $user->createToken('myapptoken')->plainTextToken;
        return response(['tokens' => $user->tokens], 201);
        return response([
            'user'=>$user,
            'token' => $token,
        ], 201);
    }
    
}
