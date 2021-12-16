<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    public $timestamps = false;

    protected $table = 'User';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'email', 'password', 'birthdate', 'profile_pic', 'cover_pic', 'priv_stat', 'admin_flag'
    ];

    protected $attributes = [
        'priv_stat' => PrivacyStatus::PublicStatus,
        'admin_flag' => false
    ];

    // I'm flying blind here
    /**
     * All the content this user has created
     */
    public function all_content() {
        return $this->hasMany(UserContent::class, 'creator')->orderBy('timestamp', 'desc');
    }

    /**
     * All this user's posts
     */
    public function posts() {
        return $this->hasMany(Post::class, 'creator')->orderBy('timestamp', 'desc');
    }

    /**
     * All this user's shares
     */
    public function shares() {
        return $this->hasMany(Share::class, 'creator')->orderBy('timestamp', 'desc');
    }

    /**
     * All this user's comments
     */
    public function comments() {
        return $this->hasMany(Comment::class, 'creator')->orderBy('timestamp', 'desc');
    }

    /**
     * All this user's friends
     */
    public function friends() {
        return $this->belongsToMany(User::class, 'friendship', 'id', 'id');
    }

    /**
     * All this user's memberships
     */
    public function groups() {
        return $this->belongsToMany(Group::class, 'membership', 'id', 'id')->withPivot('');
    }

    /**
     * Check if this user can view content
     * @var content content to check
     */
    public function canViewContent(UserContent $content) {
        if ($content->priv_stat == PrivacyStatus::PublicStatus) return true;
        else if ($content->priv_stat == PrivacyStatus::AnonymousStatus || $content->priv_stat == PrivacyStatus::BannedStatus) return false;
        else if ($content->priv_stat == PrivacyStatus::PrivateStatus && ($this->isFriend($content->creator) || $this->inGroup($content->group))) return true;
        else return false;
    }

    /**
     * Check if user is a friend of this user
     * @var user user to check
     */
    public function isFriend(User $user) {
        foreach($this->friends as $friend) {
            if ($friend == $user) return true;
        }
        return false;
    }

    /**
     * Check if this user is in group
     * @var group group to check
     */
    public function inGroup(Group $group) {
        foreach($this->groups as $memberOf) {
            if ($memberOf == $group) return true;
        }
        return false;
    }

    /**
     * Ban another user
     * @var user user to ban
     */
    public function ban(User $user) {
        if ($this->admin_flag && !$user->admin_flag) {
            $user->priv_stat = PrivacyStatus::BannedStatus;
            return true;
        }
        return false;
    }

    /**
     * Delete content
     */
    public function deleteContent(UserContent $content) {
        if ($this->admin_flag || $this == $content->creator) {
            $content->priv_stat = PrivacyStatus::AnonymousStatus;
            return true;
        }
        return false;
    }
}