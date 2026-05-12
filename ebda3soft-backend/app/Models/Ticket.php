<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_number', 'subject', 'description', 'product_id',
        'client_id', 'assigned_to', 'requester_name', 'requester_email',
        'requester_phone', 'priority', 'status', 'attachment', 'resolved_at',
    ];

    protected $casts = ['resolved_at' => 'datetime'];

    public function product()    { return $this->belongsTo(Product::class); }
    public function client()     { return $this->belongsTo(Client::class); }
    public function assignee()   { return $this->belongsTo(User::class, 'assigned_to'); }
    public function replies()    { return $this->hasMany(TicketReply::class)->orderBy('created_at'); }
}
