<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class AdminCategoryObserver
{

    public function creating(Category $category)
    {
        if(empty($category->slug)) {
            $this->replaceRepeatSlug($category);
        } else {
            $category->slug = \Str::slug($category->slug);
        }
    }

    /**
     * Handle the Category "created" event.
     *
     * @param  \App\Models\Category  $category
     * @return void
     */
    public function created(Category $category)
    {
        //
    }

    public function updating(Category $category)
    {
        if ($category->isDirty('name') || empty($category->slug)) {
            $this->replaceRepeatSlug($category);
        } else {
            $category->slug = \Str::slug($category->slug);
        }

    }

    /**
     * Handle the Category "updated" event.
     *
     * @param  \App\Models\Category  $category
     * @return void
     */
    public function updated(Category $category)
    {
        //
    }

    /**
     * Handle the Category "deleted" event.
     *
     * @param  \App\Models\Category  $category
     * @return void
     */
    public function deleted(Category $category)
    {
        Cache::forget('category');
    }

    /**
     * Handle the Category "saved" event.
     *
     * @param  \App\Models\Category  $category
     * @return void
     */
    public function saved(Category $category)
    {
        Cache::forget('category');
    }

    /**
     * Handle the Category "restored" event.
     *
     * @param  \App\Models\Category  $category
     * @return void
     */
    public function restored(Category $category)
    {
        //
    }

    /**
     * Handle the Category "force deleted" event.
     *
     * @param  \App\Models\Category  $category
     * @return void
     */
    public function forceDeleted(Category $category)
    {
        //
    }

    /**
     * @param Category $category
     * @return void
     */
    public function replaceRepeatSlug(Category $category): void
    {
        $category->slug = \Str::slug($category->name);

        $check = Category::query()->when($category->parent_id, function ($q, $parent_id) {
            return $q->where('parent_id', $parent_id);

        }, function ($q, $category) {
            return $q->where('parent_id', null);

        })->where('slug', $category->slug)->exists();


        if ($check) {
            $category->slug = \Str::slug($category->name) . time();
        }
    }
}
