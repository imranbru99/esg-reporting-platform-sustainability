<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiChatMessage extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'citations' => 'array',
    ];

    public function conversation()
    {
        return $this->belongsTo(AiChatConversation::class, 'ai_chat_conversation_id');
    }
}
