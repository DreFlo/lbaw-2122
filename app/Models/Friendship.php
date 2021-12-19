<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Friendship extends Pivot
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'friendship';

    protected $fillable = [
        'user_1', 'user_2'
    ];

    /**
     * Creates a new, bidirectional, friendship in the database
     */
    public static function create($friendshipInfo)
    {
        $attributes = ['user_1' => $friendshipInfo['user_2'], 'user_2' => $friendshipInfo['user_1']];

        (new static)->newQuery()->create($attributes);

        $attributes = ['user_1' => $friendshipInfo['user_1'], 'user_2' => $friendshipInfo['user_2']];

        return (new static)->newQuery()->create($attributes);
    }
}
