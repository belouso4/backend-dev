<?php

namespace App\Rules;

use App\Models\Category;
use Illuminate\Contracts\Validation\Rule;

class ValidSlugCategory implements Rule
{
    private $parent_id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($parent_id)
    {
        $this->parent_id = $parent_id;
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
        if($this->parent_id) {
            return !(Category::where('parent_id', $this->parent_id)
                ->where('slug', \Str::slug($value))
                ->exists());
        } else {
            return !(Category::where('parent_id', null)
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
