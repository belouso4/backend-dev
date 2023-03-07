<?php

namespace App\Repositories\Eloquent;

use App\Models\Permission;
use App\Models\Role as Model;
use App\Repositories\Contracts\IRole;

class RoleRepository extends BaseRepository implements IRole
{
    public function model()
    {
        return Model::class;
    }

    public function getRoles()
    {
        return $this->model
            ->withCount('users')
            ->paginate();
    }

    public function getRoleForEdit($id)
    {
        return $this->model
            ->where('id', '=', $id)
            ->with(['users' => function($query) {
                $query->orderBy('id','asc');
                $query->select('id', 'name');
            }])
            ->first();
    }

    public function search($query)
    {
        return $this->model
            ->where('name', 'like', "%$query%")
            ->paginate();
    }

}
