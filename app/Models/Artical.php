<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Artical extends Model
{
    use HasFactory;

    protected $fillable=['title','media','description','tags'];

    public function getTagsAttribute($value)
    {
        $decodedData = json_decode($value,true);
        $valuesArray = array_map(function ($tag) {
            return $tag['value'];
        }, $decodedData);
         
        return $valuesArray;
    }

    public function getMediaAttribute($value)
    {
        if($value)
        {
            $path=Storage::url('public/artical/'.$value);
            return  $path;
        }
        else
        {
            return null;
        }
    }
}
