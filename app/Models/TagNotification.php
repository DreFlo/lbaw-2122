<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TagNotification extends Model
{
    use HasFactory;

    protected $table = 'tag_notification';

    public function content(): BelongsTo
    {
        return $this->belongsTo(UserContent::class, 'content_id', 'id');
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_id', 'id');
    }
}
