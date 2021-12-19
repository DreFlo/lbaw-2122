<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $table = "post";

    public $fillable = [
        'pic_1', 'pic_2', 'pic_3', 'pic_4', 'pic_5'
    ];

    public function content(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(UserContent::class, 'id', 'id');
    }

    public function images(): array
    {
        $images = [];
        for ($i = 1; $i <=5; $i++) {
            $images[] = $this->pic($i)->getRelated();
        }
        return $images;
    }

    private function pic($n): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        $localKeyString = 'pic_'.$n;
        return $this->hasOne(Image::class, 'id', $localKeyString);
    }
}
