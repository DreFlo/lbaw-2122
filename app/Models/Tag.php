<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Tag extends Pivot
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'tag';

    protected $fillable = [
      'user_id', 'content_id'
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function content(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(UserContent::class, 'content_id', 'id');
    }
}
