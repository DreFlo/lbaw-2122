<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    public  $timestamps = false;

    public $table = 'image';

    protected $primaryKey = 'id';

    protected $fillable = [
        'alt', 'path'
    ];
}
