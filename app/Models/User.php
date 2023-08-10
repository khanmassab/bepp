<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Carbon;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'is_verified',
        'email',
        'password',
        'role_id',
        'website_link',
        'address',
        'post_code',
        'phone_number',
        'call_date',
        'call_from_time',
        'call_to_time',
        'member_ship_number',
        'marketing_channel',
        'marketing_source',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function getCallFromTimeAttribute($value)
    {

        return Carbon::parse($value)->format('H:i');
        // return Carbon::createFromTime('H:i',)->format('H:i');
    }
    public function getCallTOTimeAttribute($value)
    {

        return Carbon::parse($value)->format('H:i');
        // return Carbon::createFromTime('H:i',)->format('H:i');
    }
    public function providerInfo()
    {
       return $this->belongsTo(ProviderBuisness::class,'id','user_id');
    }

    public function gallery()
    {

        return $this->hasMany(ProviderGallery::class,'provider_id','id');
    }

    public function services()
    {
        return $this->hasMany(ProviderServices::class,'provider_id','id');
    }

    public function reviews()
    {

          return $this->hasManyThrough(Review::class, BookProvider::class,'provider_id','booking_id','id','id')->where('review_type','review');

    }
    public function missedApointment()
    {

     return $this->hasManyThrough(Review::class, BookProvider::class,'provider_id','booking_id','id','id')->where('review_type','missed_appointment')->whereBetween('reviews.created_at', [Carbon::now()->subMonth(6), Carbon::now()]);

    }

    public function getCreatedAtAttribute($value)
    {
        $createdAt = $value;
        $now = now();
        $days = $now->diffInDays($createdAt);
        $weeks = $now->diffInWeeks($createdAt);
        $months = $now->diffInMonths($createdAt);
        $years = $now->diffInYears($createdAt);
        $duration = '';
        if ($years > 0) {
           return $duration = $years . ' year' . ($years > 1 ? 's' : '');
        } elseif ($months > 0) {
          return  $duration = $months . ' month' . ($months > 1 ? 's' : '');
        } elseif ($weeks > 0) {
           return $duration = $weeks . ' week' . ($weeks > 1 ? 's' : '');
        } else {
           return $duration = $days . ' day' . ($days > 1 ? 's' : '');
        }
    }


}
