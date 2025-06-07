<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
      protected $fillable = [
        'message',
        'sender',         
        'conversation_id' 
    ];
  public function conversation()
{
    return $this->belongsTo(Conversation::class);
}
}
