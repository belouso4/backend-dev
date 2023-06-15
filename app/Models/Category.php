<?php

namespace App\Models;

use App\Events\CategoryDeleted;
use App\Events\CategorySaved;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $fillable = [
        'name',
        'slug',
        'parent_id',
        'order',
    ];

    public function children()
    {
        return $this->hasMany('App\Models\Category', 'parent_id')->orderBy('order')->with('children');
    }

    public function category()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function posts() {
        return $this->hasMany(Post::class);
    }
}
