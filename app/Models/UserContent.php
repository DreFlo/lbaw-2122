<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#TODO - check priv_stat and creator on fillable
class UserContent extends Model
{
    use HasFactory;

    public $table = 'user_content';

    protected $fillable = [
      'text', 'creator_id', 'group_id', 'pinned', 'priv_stat'
    ];

    /**
     * This content's group
     */
    public function group(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    /**
     * This content's creator
     */
    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }
}
