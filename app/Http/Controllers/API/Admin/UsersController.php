<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Requests\AdminUserCreateRequest;
use App\Http\Requests\AdminUserUpdateRequest;
use App\Http\Resources\Admin\User\UserResource;
use App\Models\User;
use App\Repositories\Contracts\IUser;
use Illuminate\Http\Request;
use App\Traits\ImageUploadTrait;

class UsersController extends AdminController
{
    use ImageUploadTrait;

    protected $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('can:viewAny,'.User::class)->except(['index', 'edit']);
        $this->authorizeResource(User::class, 'user');
        $this->userRepository = app(IUser::class);
    }

    public function index()
    {
        $users = $this->userRepository
            ->getAllUsersDoesntHaveRole();
        return UserResource::collection($users);
    }

    public function store(AdminUserCreateRequest $request)
    {
        $user = new User($request->all());
        $user->avatar = $this->setImage('avatar', '/avatar');

        $user->save();
        $this->uploadAvatar();
        $user->roles()->attach($request['role_id']);

        return response()->json(['id' => $user->id]);
    }

    public function edit(User $user)
    {
        return new UserResource($user);
    }

    public function update(AdminUserUpdateRequest $request, User $user)
    {
        $data = $request->all();

        switch ($request['status']) {
            case '':
                $data['status'] = $user->status;
                $data['banned_until'] = $user->banned_until;
                break;
            case 1:
            case 0:
                $data['status'] = $request['status'];
                if (!is_null($user->banned_until)) $data['banned_until'] = null;
                break;
            default:
                $data['status'] = 1;
                $data['banned_until'] = $request['status'];
                break;
        }

        $user->update($data);
        $user->roles()->sync($request['role_id']);

        if($request->hasFile('avatar')) {
            $user->avatar = $this->setImage('avatar', '/avatar', $user->avatar);
            $user->save();
            $this->updateAvatar();
        }

        return response()->json(['id' => $user->id]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }

    public function search(Request $request)
    {
        $query = $request->query('search');
        $users = $this->userRepository->search($query);

        return UserResource::collection($users);
    }
}
