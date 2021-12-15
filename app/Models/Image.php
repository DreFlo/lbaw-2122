<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public $timestamps = false;

    protected $table = 'Image';

    protected $primaryKey = 'id';

    protected $fillable = [
        'alt', 'path'
    ];
}