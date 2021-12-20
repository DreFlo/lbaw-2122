<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Comment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'comment';

    protected $guarded = [
        'id', 'parent_id'
    ];

    public function content(): BelongsTo
    {
        return $this->belongsTo(UserContent::class, 'id', 'id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(UserContent::class, 'parent_id', 'id');
    }

    public function notification(): HasOne
    {
        return $this->hasOne(CommentNotification::class, 'comment_id', 'id');
    }
}
