<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'email',
        'post_code',
        'phon_number',
        'address',
        'city',
        'country',
        'intitil_permation',
        'punctuality',
        'cleanliness',
        'quality',
        'value',
        'overall_rating',
        'is_friend',
        'work_image',
        'comment',
        'booking_id',
        'review_type',
        'review_count',
    ];


    public function getWorkImageAttribute($value)
    {
        // dd($value);
        $path=  Storage::url('public/work_images/'.$value);
        return $path;
       
    }
}
