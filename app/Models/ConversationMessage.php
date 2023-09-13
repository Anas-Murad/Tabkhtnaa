<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConversationMessage extends Model
{
    protected $fillable = [
        'conversation_id',
        'user_id',
        'message',
    ];

    public function files()
    {
        return $this->hasMany(ConversationMessageFile::class , 'message_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }



}
