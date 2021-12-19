<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class FriendRequest extends Pivot
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'friend_request';

    protected $fillable = [
      'requester_id', 'target_id', 'req_stat'
    ];
}
