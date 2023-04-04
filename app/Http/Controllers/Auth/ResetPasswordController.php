<?php
namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use App\Http\Controllers\Controller;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    public function __construct()
    {
        $this->middleware('throttle:auth');
    }

    /**
     * Validates the reset of the password
     *
     * @param Request $request The request object submitted by user
     */
    public function reset( ResetPasswordRequest $request )
    {
        $user = User::where('email', '=', $request->email)->first();

        $updatePassword = $this->broker()->tokenExists( $user, $request->token );

        if(!$updatePassword){
            return response()->json(
                'Неверный токен!',
                422,
                ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }

        $user->update(['password' => $request->password]);

        $this->broker()->getRepository()->delete( $user );

        return response()->json( 'Ваш пароль был изменен!' );
    }

}
