<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
    protected $table = 'avatar';
    protected $fillable = ['image_path', 'price'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'useravatar', 'avatar_id', 'user_id');
    }
}


