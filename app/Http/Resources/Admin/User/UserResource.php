<?php

namespace App\Http\Resources\Admin\User;

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
        $role = $this->roles()->exists()
            ? $this->roles()->first()->only(['id', 'name'])
            : '';

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => Helper::getPathIfExist('avatar/original/', $this->avatar),
            'role' => $role,
            'banned_until' => $this->banned_until,
            'status' => $this->status,
        ];
    }
}
