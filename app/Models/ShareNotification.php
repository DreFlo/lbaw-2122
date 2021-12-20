<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShareNotification extends Model
{
    use HasFactory;

    protected $table = 'share_notification';

    public function share(): BelongsTo
    {
        return $this->belongsTo(Share::class, 'share_id', 'id');
    }
}
