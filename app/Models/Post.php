<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Post extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $table = "post";

    public $fillable = [
        'pic_1', 'pic_2', 'pic_3', 'pic_4', 'pic_5'
    ];

    public function content(): BelongsTo
    {
        return $this->belongsTo(UserContent::class, 'id', 'id');
    }


    #TODO-I dont know if working
    public function images(): array
    {
        $images = [];
        if($this->pic_1 !== null) {
            $images[] = $this->image_1;
            if($this->pic_2 !== null) {
                $images[] = $this->image_2;
                if($this->pic_3 !== null) {
                    $images[] = $this->image_3;
                    if($this->pic_4 !== null) {
                        $images[] = $this->image_4;
                        if($this->pic_5 !== null) {
                            $images[] = $this->image_5;
                        }
                    }
                }
            }
        }
        return $images;
    }

    public function image_1(): HasOne
    {
        return $this->hasOne(Image::class, 'id', 'pic_1');
    }

    public function image_2(): HasOne
    {
        return $this->hasOne(Image::class, 'id', 'pic_2');
    }

    public function image_3(): HasOne
    {
        return $this->hasOne(Image::class, 'id', 'pic_3');
    }

    public function image_4(): HasOne
    {
        return $this->hasOne(Image::class, 'id', 'pic_4');
    }

    public function image_5(): HasOne
    {
        return $this->hasOne(Image::class, 'id', 'pic_5');
    }

    public function shares(): HasMany
    {
        return $this->hasMany(Share::class, 'post_id', 'id');
    }
}
