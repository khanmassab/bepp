<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProviderBuisness extends Model
{
    use HasFactory;

    protected $fillable =[
        'company_name',
        'buisness_type',
        'description',
        'logo',
        'loc_name',
        'latitude',
        'longitude',
        'category_id',
        'user_id',
        'address',
        'country',
        'city',
        'website_link'
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class,'category_id','id');
    }
    public function getLogoAttribute($value)
    {

        if($value)
        {
            $path=Storage::url('public/buisness_logo/'.$value);
            return  $path;
        }
        else
        {
            return null;
        }

    }
}
