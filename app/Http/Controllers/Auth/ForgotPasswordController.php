<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('throttle:auth');
        $this->middleware('guest');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users']);

        Password::sendResetLink($request->only('email'));

        return response()->json('Мы отправили ссылку для сброса пароля по электронной почте!');
    }
}
