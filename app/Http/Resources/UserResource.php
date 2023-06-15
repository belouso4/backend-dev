<?php

namespace App\Http\Resources;

use App\Helper\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

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
        $role = $this->getRoleSlug();
        $permis = $this->permissions();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => [
                'small' => Helper::getPathIfExist('avatar/small/', $this->avatar),
                'original' => Helper::getPathIfExist('avatar/original/', $this->avatar),
            ],
            'banned_until' => $this->banned_until,
            'status' => $this->status,
            'role' => $this->when($role, $role),
            'permission' => $this->when($permis, $permis),
            'email_verified_at'=> $this->email_verified_at
        ];
    }
}
