<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use \Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('throttle:auth');
    }

    public function register( Request $request )
    {
        $validator = Validator::make( $request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'confirm_password' => 'required|same:password'
        ]);

        if( !$validator->fails() ){
            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => $request->get('password'),
            ]);

            $user->sendApiEmailVerificationNotification();

            return response()->json('', 204 );
        }

        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    public function login( Request $request )
    {

        if (Auth::attempt( $request->only('email', 'password') )) {
            $request->session()->regenerate();

            return response()->json('', 204 );
        }else{
            return response()->json([
                'error' => 'invalid_credentials'
            ], 403);
        }
    }

    public function logout( Request $request )
    {
        auth()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json('', 204);
    }
}
