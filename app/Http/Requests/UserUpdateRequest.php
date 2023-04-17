<?php

namespace App\Http\Requests;

use App\Rules\ValidOldPassword;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:3|max:50|string',
            'email' => 'required|email|max:255|unique:users,email,'.auth()->id(),
            'old_password' => ['nullable','sometimes','required_with:new_password', new ValidOldPassword()],
            'new_password' => 'required_with:old_password',
            'confirm_password' => 'required_with:new_password|same:new_password',
            'avatar' => $this->hasFile('avatar') ? 'mimes:jpeg,jpg,png,gif|max:1024' : '', // 1 MB'
        ];
    }
}
