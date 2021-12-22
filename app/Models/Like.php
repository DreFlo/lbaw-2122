<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Like extends Pivot
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'like';

    protected $fillable = [
        'user_id', 'content_id'
    ];

    public function content(): BelongsTo
    {
        return $this->belongsTo(UserContent::class, 'id', 'id');
    }

    
}
