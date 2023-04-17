<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    use ImageUploadTrait;

    public function __construct()
    {

    }

    public function show( Request $request ){
        return new UserResource(auth()->user());
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        if ($request->exists('new_password')) {
            $request->request->add(['password' => $request['new_password']]);
        }

        $user = $request->user();
        $user->update($request->all());

        if($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $this->setImage($file, '/avatar', $user->avatar);
            $user->avatar = $this->updateAvatar();
            $user->save();
        }

        return response()->json(null);
    }
}
