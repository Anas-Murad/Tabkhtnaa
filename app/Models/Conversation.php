<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use Auditable;
    protected $fillable = [
        'user1_id',
        'user1_type',
        'user2_id',
        'user2_type',
        'user1_deleted_at',
        'user2_deleted_at',
        'order_id',
        'last_message_id',
    ];

    protected $casts = [
        'user1_deleted_at' => 'datetime',
        'user2_deleted_at' => 'datetime',
    ];


protected $hidden =[
//    'user1_deleted_at',
//    'user2_deleted_at',
];
    public function user1()
    {
        return $this->belongsTo(User::class , 'user1_id');
    }

    public function user2()
    {
        return $this->belongsTo(User::class , 'user2_id');
    }

    public function lastMessage()
    {
        return $this->belongsTo(ConversationMessage::class, 'last_message_id');
    }

    public function messages()
    {
        return $this->hasMany(ConversationMessage::class , 'conversation_id');
    }

    public function messagesFiles()
    {
        return $this->hasMany(ConversationMessageFile::class , 'conversation_id');
    }


    public function order()
    {
        return $this->belongsTo(Order::class ,'order_id');
    }

}


