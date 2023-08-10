<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProviderGallery extends Model
{
    use HasFactory;

    protected $fillable =[
        'media',
        'media_type',
        'provider_id'
    ];


    public function getMediaAttribute($value)
    {
        if($value)
        {
            $path=  Storage::url('public/media/'.$value);
            return  $path;
        }
        else
        {
            return null;
        }
       
       
    }
}
