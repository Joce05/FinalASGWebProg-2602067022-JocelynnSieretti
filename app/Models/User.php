<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'phonenumber',
        'address',
        'coin',
        'instagram',
        'hobby',
        'image'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'hobby' => 'array'
        ];
    }

    public function friendships()
    {
        return $this->hasMany(Friendship::class, 'userid');
    }

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friendships', 'userid', 'friendid');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'userid');
    }

     public function sentMessages()
     {
         return $this->hasMany(Message::class, 'senderid');
     }

     public function receivedMessages()
     {
         return $this->hasMany(Message::class, 'receiverid');
     }

     public function notifications()
    {
    return $this->hasMany(Notification::class);
    }

    public function avatars()
    {
        return $this->belongsToMany(Avatar::class, 'useravatar', 'user_id', 'avatar_id');
    }

}
