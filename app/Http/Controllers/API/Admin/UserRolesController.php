<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Resources\Admin\User\UserRolesResource;
use App\Models\Role;
use App\Repositories\Contracts\IUserRoles;
use Illuminate\Http\Request;

class UserRolesController extends AdminController
{

    protected $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = app(IUserRoles::class);
    }

    public function index()
    {
        $users = $this->userRepository->getUserWhereHasRoles();
        return UserRolesResource::collection($users);
    }

    public function search(Request $request)
    {
        $users = $this->userRepository->search($request['search']);

        return UserRolesResource::collection($users);
    }
}
