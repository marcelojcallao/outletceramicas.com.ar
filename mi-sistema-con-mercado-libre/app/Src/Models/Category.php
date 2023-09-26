<?php

namespace App\Src\Models;

use Nestable\NestableTrait;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    //use NestableTrait;

    protected $guarded =[];

    protected $fillable = [];

    protected $parent = 'parent_id';

    protected $casts = [
        'attributes' => 'array'
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'category_product', 'category_id', 'product_id');
    }

    public function category_product(): HasMany
    {
        return $this->hasMany(CategoryProduct::class, 'category_id', 'id');
    }
    
    public function setAttributes($attributes)
    {
        $attrs = [];

        foreach($attributes as $key => $value){
            $attrs[] = strtoupper($value['name']);
        }

        $this->attributes = json_encode($attrs) ;

    }

    public function isMain()
    {
        return ($this->parent_id == 0)
            ? true
            : false;
    }
}   
