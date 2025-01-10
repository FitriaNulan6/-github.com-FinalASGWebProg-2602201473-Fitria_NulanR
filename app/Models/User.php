<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'mobile_number',
        'instagram',
        'coins',
        'is_visible',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_visible' => 'boolean',
    ];

    public function hobbies()
    {
        return $this->belongsToMany(Hobby::class, 'user_hobbies');
    }

    public function givenThumbs()
    {
        return $this->hasMany(Thumb::class, 'from_user_id');
    }

    public function receivedThumbs()
    {
        return $this->hasMany(Thumb::class, 'to_user_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'from_user_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'to_user_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function hasMatchWith(User $user)
    {
        return $this->givenThumbs()
            ->where('to_user_id', $user->id)
            ->where('is_matched', true)
            ->exists();
    }
}