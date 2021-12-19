<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use HasFactory;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    public $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'birthdate','email', 'password', 'profile_pic', 'cover_pic', 'priv_stat'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * This user's cover picture.
     */
     public function coverPic(): \Illuminate\Database\Eloquent\Relations\HasOne
     {
      return $this->hasOne(Image::class, 'id', 'cover_pic');
    }

    /**
     * This user's profile picture
     */
    public function profilePic(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Image::class, 'id', 'profile_pic');
    }

    /**
     * This user's friends
     */
    public function friends(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friendship', 'user_1');
    }

    /**
     * This user's groups
     */
    public function groups(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'membership', 'user_id', 'group_id')->using(Membership::class);
    }

    public function moderatedGroups(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'membership', 'user_id', 'group_id')->using(Membership::class)->wherePivot('moderator', true);
    }
}
