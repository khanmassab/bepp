<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuestionAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'question_detail',
        'detail',
        'answer',
        'media',
    ];

    public function getMediaAttribute($value)
    {
        if($value)
        {
            $path=Storage::url('public/question_Answer/'.$value);
            return  $path;
        }
        else
        {
            return null;
        }
    }
    
}
