<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $table = 'group';

    protected $fillable = [
        'name', 'cover_pic', 'creator_id', 'priv_stat'
    ];

    public function members(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'membership', 'group_id', 'user_id')->using(Membership::class);
    }

    public function moderators(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'membership', 'group_id', 'user_id')->using(Membership::class)->wherePivot('moderator', true);
    }

    public function coverPic(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Image::class, 'id', 'cover_pic');
    }

    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }
}
