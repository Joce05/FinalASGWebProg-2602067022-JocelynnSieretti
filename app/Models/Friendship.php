<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    //
    protected $fillable = ['userid', 'friendid'];

    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }

    public function friend()
    {
        return $this->belongsTo(User::class, 'friendid');
    }
}
