<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class Image extends Model
{
    use HasFactory;

    public  $timestamps = false;

    public $table = 'image';

    protected $primaryKey = 'id';

    protected $fillable = [
        'alt', 'path'
    ];

    public static function storeAndRegister(UploadedFile $image): int
    {
        $path = $image->store('images','public');

        return DB::table('image')->insertGetId([
            'path' => '/storage/'.$path
        ]);
    }
}
