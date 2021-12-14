<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AuthenticatedUser extends Autheticatable
{
    use Notifiable;

    public $timestamps = false;

    protected $table = 'AuthenticatedUser';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'email', 'password', 'birthdate', 'profile_pic', 'cover_pic', 'priv_stat'
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
        return $this->belongsToMany(AuthenticatedUser::class, 'friendship');
    }

    /**
     * All this user's memberships
     */
    public function groups() {
        return $this->belengsToMany(Group::class, 'membership')->withPivot('');
    }

    /**
     * Check if this user can view content
     * @var content content to check
     */
    public function canViewContent(UserContent $content) {
        if ($content->priv_stat == 'Public') return true;
        else if ($content->priv_stat == 'Anonymous' || $content->priv_stat == 'Banned') return false;
        else if ($content->priv_stat == 'Private' && ($this->isFriend($content->creator) || $this->inGroup($content->group))) return true;
        else return false;
    }

    /**
     * Check if user is a friend of this user
     * @var user user to check
     */
    public function isFriend(AutheticatedUser $user) {
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
}