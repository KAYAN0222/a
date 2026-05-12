<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketReply extends Model
{
    protected $fillable = ['ticket_id', 'user_id', 'author_name', 'message', 'is_staff', 'attachment'];
    protected $casts = ['is_staff' => 'boolean'];

    public function ticket() { return $this->belongsTo(Ticket::class); }
    public function user()   { return $this->belongsTo(User::class); }
}
