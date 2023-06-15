<?php
namespace App\Traits;

use App\Models\Role;
use App\Models\Permission;

trait HasRolesAndPermissions {

    public function roles()
    {
        return $this->belongsToMany(Role::class,'users_roles');
    }

    public function getRoleSlug()
    {
        return $this->roles->first() ? $this->roles->first()->slug : '';
    }

    public function permissions()
    {
        return $this->roles()->with('permissions')->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('name')
            ->unique()->toArray();
    }

    public function hasRole(...$roles) {
        foreach ($roles as $role) {
            if ($this->roles->contains('slug', $role)) {
                return true;
            }
        }
        return false;
    }

    public function hasPermissionThroughRole($permission)
    {
        foreach ($permission->roles as $role){
            if($this->roles->contains($role)) {
                return true;
            }
        }
        return false;
    }

    public function checkPermission($name) {
        $permissions = $this->roles()->first()
            ->permissions
            ->pluck('name');

        foreach ($permissions as $permission) {
            if($permission === $name) {
                return true;
            }
        }
        return false;
    }



}
