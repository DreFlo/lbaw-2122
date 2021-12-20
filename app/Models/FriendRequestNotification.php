<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FriendRequestNotification extends Model
{
    use HasFactory;

    protected $table = 'friend_request_notification';

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_id', 'id');
    }

    public function request()
    {
        return $this->target->incomingFriendRequests()->wherePivot('requester_id', $this->sender->id);
    }
}
