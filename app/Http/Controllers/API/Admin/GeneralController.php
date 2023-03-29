<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminGeneralProfileRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Resources\Admin\Post\PostResource;
use App\Models\Post;
use App\Models\User;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GeneralController extends Controller
{
    use ImageUploadTrait;

    public function profile(AdminGeneralProfileRequest $request)
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

    public function search(SearchRequest $request)
    {
        $search = $request->query('search');
        $data = [];

          $columns = ['title', 'id', 'img'];
        $data['posts'] = Post::query()
            ->where('title', 'like', "%$search%")
            ->orderBy('created_at', 'DESC')
            ->get($columns);

        $columns = ['name as title', 'id', 'avatar as img', 'email'];
        $data['users'] = User::query()
            ->where('name', 'like', "%$search%")
            ->orWhere('email', 'like', "%$search%")
            ->orderBy('created_at', 'DESC')
            ->get($columns);

        if ($data['posts']->count() === 0) unset($data['posts']);
        if ($data['users']->count() === 0) unset($data['users']);

        return $data;

    }
}
