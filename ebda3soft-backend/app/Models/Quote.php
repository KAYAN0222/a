<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $fillable = [
        'quote_number', 'product_id', 'company_name', 'contact_name',
        'phone', 'email', 'city', 'employees_count', 'requirements',
        'attachment', 'status', 'admin_notes', 'quoted_price',
    ];

    public function product() { return $this->belongsTo(Product::class); }
}
