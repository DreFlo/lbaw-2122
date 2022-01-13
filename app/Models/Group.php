<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

class Group extends Model
{
    use HasFactory;
    use Notifiable;

    public $timestamps = false;

    public $table = 'group';

    protected $fillable = [
        'name', 'cover_pic', 'creator_id', 'priv_stat'
    ];

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'membership', 'group_id', 'user_id')
                    ->using(Membership::class);
    }


    public function moderators(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'membership', 'group_id', 'user_id')
                    ->using(Membership::class)->wherePivot('moderator', true);
    }

    public function coverPic(): HasOne
    {
        return $this->hasOne(Image::class, 'id', 'cover_pic');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    public function userRequests(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_request', 'group_id', 'user_id')
                    ->withPivot('req_stat', 'invite')->using(GroupRequest::class)
                    ->wherePivot('invite', false);
    }

    public function userInvites(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_request', 'group_id', 'user_id')
                    ->withPivot('req_stat', 'invite')
                    ->using(GroupRequest::class)->wherePivot('invite', true);
    }

    public function userRequestNotifications(): HasMany
    {
        return $this->hasMany(GroupRequestNotification::class, 'group_id', 'id');
    }

    public function allContent(): HasMany
    {
        return $this->hasMany(UserContent::class, 'group_id', 'id');
    }

    public function posts(): HasManyThrough
    {   
        return $this->hasManyThrough(Post::class, UserContent::class, 'group_id', 'id', 'id', 'id');
    }

    public function comments(): HasManyThrough
    {
        return $this->hasManyThrough(Comment::class, UserContent::class, 'group_id', 'id', 'id', 'id');
    }

    public function shares(): HasManyThrough
    {
        return $this->hasManyThrough(Share::class, UserContent::class, 'group_id', 'id', 'id', 'id');
    }

    public function isMember(User $user): bool
    {
        foreach ($this->members as $member) {
            if ($user->id == $member->id) return true;
        }
        return false;
    }

    public function isModerator(User $user): bool
    {
        foreach ($this->moderators as $moderator) {
            if ($user->id == $moderator->id) return true;
        }
        return false;
    }
}
