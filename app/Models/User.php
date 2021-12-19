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
        return $this->belongsToMany(User::class, 'friendship', 'user_1', 'user_2')->using(Friendship::class);
    }

    /**
     * This user's groups
     */
    public function groups(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'membership', 'user_id', 'group_id')->withPivot('moderator')->using(Membership::class);
    }

    public function moderatedGroups(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'membership', 'user_id', 'group_id')->using(Membership::class)->wherePivot('moderator', true);
    }

    public function taggedIn(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(UserContent::class, 'tag', 'user_id', 'content_id')->using(Tag::class);
    }

    public function likes(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'like', 'user_id', 'content_id')->using(Like::class);
    }

    public function outgoingFriendRequests(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friend_request', 'requester_id', 'target_id')->withPivot('req_stat')->using(FriendRequest::class);
    }

    public function incomingFriendRequests(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friend_request', 'target_id', 'requester_id')->withPivot('req_stat')->using(FriendRequest::class);
    }

    public function groupRequests(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_request', 'user_id', 'group_id')->withPivot('req_stat', 'invite')->using(GroupRequest::class)->wherePivot('invite', false);
    }

    public function groupInvites(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_request', 'user_id', 'group_id')->withPivot('req_stat', 'invite')->using(GroupRequest::class)->wherePivot('invite', true);
    }
}
