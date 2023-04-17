<?php

namespace App\Http\Requests;

use App\Rules\ValidSlugCategory;
use App\Rules\ValidSlugCategoryUpdate;
use Illuminate\Foundation\Http\FormRequest;

class AdminCategoryCreateRequest extends FormRequest
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
       $ValidSlugCategory =  $this->id
           ? new ValidSlugCategoryUpdate($this->parent_id, $this->id)
           : new ValidSlugCategory($this->parent_id);

        return [
            'name' => 'required|max:255',
            'slug' => ['nullable','max:255', $ValidSlugCategory],
            'parent_id' => ['nullable','numeric','exists:categories,id'] ,
        ];
    }
}
