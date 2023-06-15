<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    use ImageUploadTrait;

    public function __construct()
    {

    }

    public function show( Request $request ){
        return new UserResource(auth()->user());
    }

    public function update(UserUpdateRequest $request)
    {
        if ($request->exists('new_password')) {
            $request->request->add(['password' => $request['new_password']]);
        }

        $user = $request->user();
        $user->update($request->all());

        if($request->hasFile('avatar')) {
            $user->avatar = $this->setImage('avatar', 'avatar/', $user->avatar);
            $user->save();
            $this->updateAvatar();
        }

        return response()->json(null, 204);
    }
}
