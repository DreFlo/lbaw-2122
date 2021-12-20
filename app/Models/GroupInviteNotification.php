<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupInviteNotification extends Model
{
    use HasFactory;

    protected $table = 'group_invite_notification';

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function invite()
    {
        return $this->user->groupInvites()->wherePivot('group_id', $this->group->id);
    }
}
