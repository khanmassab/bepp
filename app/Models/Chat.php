<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
    protected $fillable =[
        'message',
        'sender_id',
        'receiver_id',
        'seen',
    ];

    public function userlist()
    {
        return $this->belongsTo(User::class,'sender_id','id');
    }
}
