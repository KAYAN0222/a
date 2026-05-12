<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductFeature extends Model
{
    public $timestamps = false;
    protected $fillable = ['product_id','feature_ar','feature_en','icon','sort_order'];
}
