<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserContent extends Model
{
    protected $table = 'UserContent';

    protected $primaryKey = 'id';

    protected $fillable = [
        'text', 'creator', 'edited', 'group', 'pinned', 'priv_stat'
    ];

    protected $attributes = [
        'priv_stat' => PrivacyStatus::PublicStatus
    ];

    public function creator() {
        return $this->hasOne(User::class, 'id');
    }

    public function group() {
        return $this->hasOne(Group::class, 'id');
    }
}
