<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAvatar extends Model
{
    //

    protected $table = 'useravatar';

    protected $fillable = [
        'user_id',
        'avatar_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function avatar()
    {
        return $this->belongsTo(Avatar::class);
    }
}
