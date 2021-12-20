<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Share extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'share';

    protected $guarded = [
        'id', 'post_id'
    ];

    public function content(): BelongsTo
    {
        return $this->belongsTo(UserContent::class, 'id', 'id');
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }

    public function notification(): HasOne
    {
        return $this->hasOne(ShareNotification::class, 'share_id', 'id');
    }
}
