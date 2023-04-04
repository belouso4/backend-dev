<?php

namespace App\Http\Resources\Admin\Role;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{

    public static $wrap = '';

    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'desc' => $this->desc ? $this->desc : '',
            'permissions_group' => $this->permissions_group,
            'users' => $this->users,
        ];

    }
}
