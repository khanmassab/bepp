<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookProvider extends Model
{
    use HasFactory;

    protected $fillable =[
        'name',
        'email',
        'phone_number',
        'address',
        'description',
        'provider_id',
        'status',
        'start_date',
        'booking_id',
    ];
}
