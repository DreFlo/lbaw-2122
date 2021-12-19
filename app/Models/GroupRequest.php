<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class GroupRequest extends Pivot
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'group_request';

    protected $fillable = [
      'user_id', 'group_id', 'req_stat'
    ];
}
