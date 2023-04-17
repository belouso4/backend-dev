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
        $exist_role = $this->roles()->exists();
        $permission = $exist_role ? $this->roles()->first()->permissions->pluck('name') : '';
        $role = $exist_role ? $this->roles()->first()->slug : '';

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => Storage::url($this->avatar),
            'is_admin' => $this->when($exist_role, $exist_role),
            'banned_until' => $this->banned_until,
            'status' => $this->status,
            'role' => $this->when($exist_role, $role),
            'permission' => $this->when($exist_role, $permission),
            'email_verified_at'=> $this->email_verified_at
        ];
    }
}
