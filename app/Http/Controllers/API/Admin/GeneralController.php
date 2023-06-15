<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Requests\AdminGeneralProfileRequest;
use App\Traits\ImageUploadTrait;

class GeneralController extends AdminController
{
    use ImageUploadTrait;

    public function profile(AdminGeneralProfileRequest $request)
    {
        $user = $request->user();
        if (!empty($request['new_password']))
            $request->request->add(['password' => $request['new_password']]);

        $user->update($request->all());

        if($request->hasFile('avatar')) {
            $user->avatar = $this->setImage('avatar', 'avatar', $user->avatar);
            $user->save();
            $this->updateAvatar();
        }

        return response()->json(null, 204);
    }
}
