<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'manufacturer_id',
        'sub_category_id',
        'category_id',
        'name',
        'model_code',
        'description',
        'retail_price',
        'warranty',
        'availability',
        'category_id',
    ];

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class);
    }
}

