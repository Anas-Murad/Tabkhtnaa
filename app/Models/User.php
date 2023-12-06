<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use  Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "id", "name", "email", "residence_country_id", "country_code", "mobile", "username",
        "dob", "gender", "source", "udid", "def_lang", "profile_image", "mobile_verified",
        "online_status", "type", "account_status", "account_comment", "email_verified_at",
        "sms_verify", "password", "remember_token", "created_at", "updated_at", 'can_delivery' ,
        'last_process_at',
        'last_distinction_at',
        'prev_distinction_at',
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
        'mobile_verified' => 'boolean',
        'can_delivery' => 'boolean',
    ];


    public function residenceCountry(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'residence_country_id');
    }

    public function setUsernameAttribute($value)
    {
        $temp = Str::slug($this->attributes['name']) . '@tabketna.com';
        $username = $temp;
        $i = 0;
        while (User::whereUsername($username)->exists()) {
            $i++;
            $username = Str::random(2) . $i . $temp;
        }
        $this->attributes['username'] = $username;
    }


    public function ApiCreateToken()
    {
        $this->tokens()->delete();
        $tokenResult = $this->createToken('authToken')->plainTextToken;
        $this->access_token = $tokenResult;
    }

    public function galleryKitchen()
    {
        return $this->hasMany(Gallery::class)->where('type', '!=', 'galleryKitchen');
    }


    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    public function meals()
    {
        return $this->hasMany(Meal::class);
    }

    public function liveLocation()
    {
        return $this->hasOne(UserLiveLocation::class, 'user_id');
    }

    public function loadRates()
    {

//    $Rating_count = Rating::where('chef_id' , $this->id)->count();
        $delivery = Rating::where('chef_id', $this->id)->avg('rating_delivery');
        $speed_chef = Rating::where('chef_id', $this->id)->avg('rating_speed_chef');
        $speed_delivery = Rating::where('chef_id', $this->id)->avg('rating_speed_delivery');
        $rating_chef = Rating::where('chef_id', $this->id)->avg('rating_chef');
        $this->raties = [
            "rating_speed" => $rating_chef,
            "rating_delivery" => $delivery,
            "rating_speed_chef" => $speed_chef,
            "rating_speed_delivery" => $speed_delivery,
        ];
    }

    public function userAddress()
    {

        //
        //

        return $this->hasMany(UserAddress::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function scopeNearby($query, $latitude, $longitude, $distance)
    {
        $query->selectRaw("
            ('6371' * ACOS(
                COS(RADIANS($latitude)) * COS(RADIANS(user_addresses.latitude)) *
                COS(RADIANS(user_addresses.longitude) - RADIANS($longitude)) +
                SIN(RADIANS($latitude)) * SIN(RADIANS(user_addresses.latitude))
            )) AS distance")
            ->having('distance', '<', $distance)->orderBy('distance', 'ASC');
    }


    public function userOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id');
    }
    public function chefOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'chef_id');
    }


    public function deliveryOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'delivery_id');
    }

    public function chefTransfers(): HasMany
    {
        return $this->hasMany(Transfer::class, 'to_id')->where('to_type', 'chef');
        //enum('admin', 'client', 'delivery', 'chef')
    }

    public function deliveryTransfers(): HasMany
    {
        return $this->hasMany(Transfer::class, 'to_id')->where('to_type', 'delivery');
    }


    public function userPointTransfers(): HasMany
    {
        return $this->hasMany(UserPointTransfers::class, 'user_id');
    }

    public function userPoints(): HasMany
    {
        return $this->hasMany(UserPoint::class, 'user_id');
    }

    public function sanctions(): HasMany
    {
        return $this->hasMany(Sanction::class, 'user_id');
    }

    public function userDistinctions(): HasMany
    {
        return $this->hasMany(UserDistinction::class, 'user_id');
    }


    public function transferRecords(): HasMany
    {
        return $this->hasMany(TransferRecord::class, 'to_id');
    }

    public function userDriverCashes(): HasMany
    {
        return $this->hasMany(UserDriverCash::class, 'user_id');
    }
}
