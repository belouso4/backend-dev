<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Requests\AdminRoleCreateRequest;
use App\Http\Requests\AdminRoleUpdateRequest;
use App\Http\Resources\Admin\Role\RoleResource;
use App\Http\Resources\Admin\Role\RolesResource;
use App\Http\Resources\Admin\User\UserResource;
use App\Models\Role;
use App\Models\User;
use App\Repositories\Contracts\IPermission;
use App\Repositories\Contracts\IRole;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;

class RolesController extends AdminController
{
    use ImageUploadTrait;

    protected $roleRepository;
    protected $permissionRepository;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('can:viewAny,'.Role::class)->except(['index', 'edit']);
        $this->authorizeResource(Role::class, 'role');
        $this->roleRepository = app(IRole::class);
        $this->permissionRepository = app(IPermission::class);
    }

    public function index()
    {
        $roles = $this->roleRepository->getRoles();
        return RolesResource::collection($roles);
    }

    public function create()
    {

    }

    public function store(AdminRoleCreateRequest $request)
    {
        $role = Role::create($request->all('name'));
        return response()->json(['id' => $role->id]);
    }

    public function show(Role $role)
    {
        //
    }

    public function edit(Role $role)
    {
        $role = $this->roleRepository->getRoleForEdit($role->id);
        return new RoleResource($role);
    }

    public function update(AdminRoleUpdateRequest $request, Role $role)
    {
        $role->update($request->all());

        $ids = $request['users'] ?? [];

        if(isset($request['new_user'])) {
            $users = $this->createUsers($request['new_user']);
            $ids = array_merge($ids, $users);
        }

        $role->users()->sync($ids);
        return response()->json(null, 204);
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json(null, 204);
    }

    public function search(Request $request)
    {
        $query = $request->query('search');
        $roles = $this->roleRepository->search($query);

        return RolesResource::collection($roles);
    }

    public function permissions() {
        $permissions_row = $this->permissionRepository->all();

        $permissions = array();
        foreach ($permissions_row as $permission) {
            if(!in_array($permission['group_slug'], array_keys($permissions))) {
                $permissions[$permission['group_slug']] = [
                    'group_slug' => $permission['group_slug'],
                    'group_name' => $permission['group_name'],
                    'actions' => [$permission['id'] => $permission['name']]
                ];
            } else {
                $permissions[$permission['group_slug']]['actions'][$permission['id']]
                    = $permission['name'];
            }
        }

         return response()->json($permissions);
    }

    public function permissionUpdate(Role $role, Request $request) {
        //it is forbidden to change the role with id 1 and permissions under id c 13-16
        if($role->id === 1 && (13 <= $request['id']) && ($request['id'] <= 16)) {
            abort(403, 'Запрещено изменять разрешения.');
        }
        $request->validate([
            'id' => 'required|exists:permissions'
        ]);

        $role->permissions()->toggle($request['id']);
        $permission = $this->permissionRepository->find($request['id']);

        return response()->json($permission);
    }

    public function setDefaultPermissions(Role $role)
    {
        if($role->id === 1) abort(403, 'Запрещено изменять разрешения.');
        $role->permissions()->sync([6,2,10]);
        return response()->json($role->permissions_group);
    }


    public function setMinimumPermissions(Role $role)
    {
        if($role->id === 1) abort(403, 'Запрещено изменять разрешения.');
        $role->permissions()->sync([]);
        return response()->json($role->permissions_group);
    }

    /**
     * Creates users and returns IDs.
     */
    private function createUsers($users) {
        foreach ($users as $key => $val) {
            $user = new User($val);
            $pathFile = 'new_user.'.$key.'.avatar';

            if(request()->hasFile($pathFile)) {
                $file = request()->file($pathFile);
                $this->setImage($file, '/avatar');
                $user->avatar = $this->uploadAvatar();
            }

            $user->save();
            $ids[] = $user->id;
        }
        return $ids;
    }

}
