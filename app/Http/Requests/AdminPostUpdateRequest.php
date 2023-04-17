<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminPostUpdateRequest extends FormRequest
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
            'title' => "required|min:3|max:255|string|unique:posts,title,$this->id",
            'desc' => 'required|min:5|string',
            'img' => $this->hasFile('img') ? 'mimes:jpeg,jpg,png,gif|max:1024' : '', // 1 MB
            'status' => 'required|in:0,1',
            'tags' => 'array',
            'category_id' => 'nullable|exists:categories,id'
        ];
    }
}
