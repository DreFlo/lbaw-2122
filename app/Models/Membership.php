<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Membership extends Pivot
{
    use HasFactory;

    public $timestamps = false;

    public $table = 'membership';

    protected $fillable = [
        'user_id', 'group_id'
    ];
}
