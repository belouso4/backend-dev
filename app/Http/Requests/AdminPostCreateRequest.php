<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminPostCreateRequest extends FormRequest
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
            'title' => 'required|min:3|max:255|string|unique:posts,title',
//            'slug' => 'required|alpha_dash|min:3|max:255|string|unique:posts,slug',
            'desc' => 'required|min:5|string',
            'img' => 'required|mimes:jpeg,jpg,png,gif|max:1024', // 1 MB'
            'status' => 'required|in:0,1',
            'category_id' => 'required|numeric|exists:categories,id'
        ];
    }
}
