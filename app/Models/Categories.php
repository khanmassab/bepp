<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categories extends Model
{
    use HasFactory;
    protected $fillable = ['title','image','status','category_type'];


    public function getImageAttribute($value)
    {
        if($value)
        {
            $path=  Storage::url('public/categories_images/'.$value);
            return  $path;
        }
        else
        {
            return null;
        }
  
       
    }
}

