<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    //
    protected $fillable = ['userid', 'desired_userid'];

    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'desired_userid');
    }
}
