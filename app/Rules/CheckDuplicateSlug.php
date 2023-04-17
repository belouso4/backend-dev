<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckDuplicateSlug implements Rule
{
    public $categories;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($categories)
    {
        $this->categories = $categories;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $parentIdsUnique = array_unique(array_column($this->categories, 'parent_id'));
        $result = true;

        foreach ($parentIdsUnique as $category) {
            $filtered_array = array_filter($this->categories, function ($obj) use ($category) {
                return $obj['parent_id'] == $category;
            });

            if (count($filtered_array) >= 2) {
                $valueArr = array_column($filtered_array, 'slug');

                if (count($valueArr) !== count(array_unique($valueArr))) {
                    $result = false;
                    break;
                }
            }
        }

        return $result;
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
