<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';


    protected $fillable = [
        'title',
        'news_details',
        'image',
        'image_title'
    ];

    /**
     * Get the image attribute.
     *
     * @param mixed $value The value to get the image attribute from.
     * @return string|null The URL of the image or null if there is no value.
     */

    public function getImageAttribute($value)
    {
        return $value ? Storage::url('public/news/' . $value) : null;
    }

}
