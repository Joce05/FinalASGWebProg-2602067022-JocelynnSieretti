<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //
    protected $fillable = [
        'senderid',
        'receiverid',
        'message',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'senderid');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiverid');
    }
}
