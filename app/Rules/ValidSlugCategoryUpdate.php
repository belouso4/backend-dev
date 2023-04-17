<?php

namespace App\Rules;

use App\Models\Category;
use Illuminate\Contracts\Validation\Rule;

class ValidSlugCategoryUpdate implements Rule
{
    public $parent_id;
    public $id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($parent_id, $id)
    {
        $this->parent_id = $parent_id;
        $this->id = $id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
//        \Log::info($this->parent_id);
        if($this->parent_id) {
            return !(Category::where('parent_id', $this->parent_id)
                ->where('id','!=', $this->id)
                ->where('slug', \Str::slug($value))
                ->exists());
        } else {
            return !(Category::where('parent_id', null)
                ->where('id','!=', $this->id)
                ->where('slug', \Str::slug($value))
                ->exists());
        }

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Повторяющийся url категории.';
    }
}
