<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConversationMessageFile extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'conversation_id',
        'message_id',
        'path',
    ];

    public function message()
    {
        return $this->belongsTo(ConversationMessage::class , 'message_id');
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class , 'conversation_id');
    }


}
