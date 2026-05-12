<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotFaq extends Model
{
    protected $table = 'chatbot_faqs';
    protected $fillable = ['question', 'answer', 'keywords', 'category', 'sort_order', 'is_active'];
    protected $casts = ['keywords' => 'array', 'is_active' => 'boolean'];
}
