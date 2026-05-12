<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number','client_id','product_id','branch_id',
        'assigned_to','status','notes','requested_at','completed_at',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function client()       { return $this->belongsTo(Client::class); }
    public function product()      { return $this->belongsTo(Product::class); }
    public function branch()       { return $this->belongsTo(Branch::class); }
    public function assignedUser() { return $this->belongsTo(User::class, 'assigned_to'); }
}
