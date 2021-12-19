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
}
