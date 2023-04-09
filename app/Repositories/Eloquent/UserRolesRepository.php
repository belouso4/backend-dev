<?php
namespace App\Repositories\Eloquent;

use App\Models\User as Model;
use App\Repositories\Contracts\IUserRoles;

class UserRolesRepository extends BaseRepository implements IUserRoles {

    public function model()
    {
        return Model::class;
    }

    public function getUserWhereHasRoles() {
        return $this->model->has('roles')
            ->with('roles')
            ->paginate();
    }

    public function search($query)
    {
        return $this->model::whereHas('roles')
            ->where('name', 'like', "$query%")
            ->with('roles')
            ->paginate();
    }
}
