<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Advertisement extends Model
{
    use HasFactory;

    protected $fillable =[
        'title',
        'description',
        'video_link',
        'type',
        'media'
    ];

    public function getMediaAttribute($value)
    {
      
        $path=  Storage::url('public/adverisment/'.$value);

        return $path;
      
    }
}
