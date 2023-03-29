<?php

namespace App\Http\Resources;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public static $wrap = '';

    public function toArray($request)
    {
//        $permission = $this->roles()->with('permissions')->get()->pluck('permissions')->flatten(1)->pluck('slug');
//        $permission = Role::find($this->roles()->id)->permissions->pluck('slug');

        $permission = $this->roles()->exists()
            ? $this->roles()->first()->permissions->pluck('name')
            : '';

        $role = $this->roles()->exists()
            ? $this->roles()->first()->slug
            : '';

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => Storage::url($this->avatar),
            'is_admin' => $this->roles()->exists(),
            'banned_until' => $this->banned_until,
            'status' => $this->status,
            'role' => $role,
            'permission' => $permission,
            'email_verified_at'=> $this->email_verified_at
        ];
    }
}
