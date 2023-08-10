<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    use HasFactory;

    protected $fillable=[
        'product_id',
        'renewal_date',
        'title',
        'name',
        'phone_number',
        'email',
        'post_code',
        'status',
        'contact_on',
    ];

    public function getContactOnAttribute($value)
    {
   
      $decodedData = json_decode(stripslashes($value), true);
     return $decodedData;
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
