<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public $timestamps = false;

    protected $table = 'Post';

    protected $primaryKey = 'id';

    protected $autoincrement = false;

    public function id() {
        return $this->belongsTo(UserContent::class, 'id');
    }

    public function images() {
        return $this->belongsToMany(Image::class);
    }
}