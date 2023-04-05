<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminMailRequest extends FormRequest
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
            'to' => $this['to'] === 0 ? 'required|email' : '',
            'select' => 'required|numeric',
            'subject' => 'required|min:3|max:255',
            'message' => 'required|min:3|max:50000',
            'attachment.*' => 'file',
        ];
    }
}
