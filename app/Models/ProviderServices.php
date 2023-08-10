<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderServices extends Model
{
    use HasFactory;

    protected $fillable=[ 
        'title',
        'provider_id',
    ];
}
