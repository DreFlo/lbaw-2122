<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LikeNotification extends Model
{
    use HasFactory;

    protected $table = 'like_notification';

    public function content(): BelongsTo
    {
        return $this->belongsTo(UserContent::class, 'content_id', 'id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }


    //TODO - can we use other packages https://github.com/staudenmeir/belongs-to-through (May not be needed)
}
