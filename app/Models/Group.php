<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use Notifiable;

    public $timestamps = false;

    protected $table = 'Group';

    protected $primaryKey = 'id';

    const CREATED_AT = 'creation_date';

    protected $fillable = [
        'name', 'cover_pic', 'priv_stat', 'creator'
    ];

    protected $attributes = [
        'priv_stat' => PrivacyStatus::PublicStatus
    ];

    /**
     * All the content created in this group
     */
    public function all_content() {
        return $this->hasMany(UserContent::class, 'group')->orderBy('timestamp', 'desc');
    }

    /**
     * All the post made in this group
     */
    public function posts() {
        return $this->hasMany(Post::class, 'group')->orderBy('timestamp', 'desc');
    }

    /**
     * All the shares made in this group
     */
    public function shares() {
        return $this->hasMany(Share::class, 'group')->orderBy('timestamp', 'desc');
    }

    /**
     * All the comments made in this group
     */
    public function comments() {
        return $this->hasMany(Comment::class, 'group')->orderBy('timestamp', 'desc');
    }

    /**
     * This group's creator
     */
    public function creator() {
        return $this->hasOne(User::class, 'id');
    }

    /**
     * This group's cover picture
     */
    public function cover_pic() {
        return $this->hasOne(Image::class, 'id');
    }
}