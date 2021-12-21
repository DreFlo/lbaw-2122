<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;

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
     public function coverPic(): HasOne
     {
      return $this->hasOne(Image::class, 'id', 'cover_pic');
    }

    /**
     * This user's profile picture
     */
    public function profilePic(): HasOne
    {
        return $this->hasOne(Image::class, 'id', 'profile_pic');
    }

    /**
     * This user's friends
     */
    public function friends(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friendship', 'user_1', 'user_2')
                    ->using(Friendship::class);
    }

    /**
     * This user's groups
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'membership', 'user_id', 'group_id')
                    ->withPivot('moderator')->using(Membership::class);
    }

    public function moderatedGroups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'membership', 'user_id', 'group_id')
                    ->using(Membership::class)->wherePivot('moderator', true);
    }

    public function taggedIn(): BelongsToMany
    {
        return $this->belongsToMany(UserContent::class, 'tag', 'user_id', 'content_id')
                    ->using(Tag::class);
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'like', 'user_id', 'content_id')
                    ->using(Like::class);
    }

    public function outgoingFriendRequests(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friend_request', 'requester_id', 'target_id')
                    ->withPivot('req_stat')->using(FriendRequest::class);
    }

    public function incomingFriendRequests(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friend_request', 'target_id', 'requester_id')
                    ->withPivot('req_stat')->using(FriendRequest::class);
    }

    public function groupRequests(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_request', 'user_id', 'group_id')
                    ->withPivot('req_stat', 'invite')->using(GroupRequest::class)->wherePivot('invite', false);
    }

    public function groupInvites(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_request', 'user_id', 'group_id')
                    ->withPivot('req_stat', 'invite')->using(GroupRequest::class)->wherePivot('invite', true);
    }

    public function likeNotifications(): HasManyThrough
    {
        return $this->hasManyThrough(LikeNotification::class, UserContent::class, 'creator_id', 'content_id', 'id', 'id');
    }

    public function allContent(): HasMany
    {
        return $this->hasMany(UserContent::class, 'creator_id', 'id');
    }

    public function posts(): HasManyThrough
    {
        return $this->hasManyThrough(Post::class, UserContent::class, 'creator_id', 'id', 'id', 'id');
    }

    public function comments(): HasManyThrough
    {
        return $this->hasManyThrough(Comment::class, UserContent::class, 'creator_id', 'id', 'id', 'id');
    }

    public function shares(): HasManyThrough
    {
        return $this->hasManyThrough(Share::class, UserContent::class, 'creator_id', 'id', 'id', 'id');
    }

    /**
     * Get all this user's comment notifications
     * @return Collection
     */
    public function commentNotifications(): Collection
    {
        //TODO - caching on notifications (I may be being stupid)?
        $notifications = collect();

        foreach ($this->allContent as $content)
        {
            foreach ($content->comments as $comment)
            {
                $notifications[] = $comment->notification;
            }
        }

        return $notifications;
    }

    public function tagNotifications(): HasMany
    {
        return $this->hasMany(TagNotification::class, 'target_id', 'id');
    }

    public function shareNotifications(): Collection
    {
        $notifications = collect();

        foreach ($this->posts as $post)
        {
            foreach ($post->shares as $share)
            {
                $notifications[] = $share->notification;
            }
        }

        return $notifications;
    }

    public function groupInviteNotifications(): HasMany
    {
        return $this->hasMany(GroupInviteNotification::class, 'user_id', 'id');
    }

    public function friendRequestNotifications(): HasMany
    {
        return $this->hasMany(FriendRequestNotification::class, 'target_id', 'id');
    }

    public function isFriend(User $user): bool
    {
        foreach ($this->friends as $friend) {
            if ($user->id === $friend->id) return true;
        }
        return false;
    }

    public function isAdmin() {
        return $this->admin_flag;
    }
}
