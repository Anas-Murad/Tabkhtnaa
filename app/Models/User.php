<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use  Str ;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "id","name","email","residence_country_id","country_code","mobile","username",
        "dob","gender","source","udid","def_lang","profile_image","mobile_verified",
        "online_status","type","account_status","account_comment","email_verified_at",
        "password","remember_token","created_at","updated_at",
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
    ];

    public function setUsernameAttribute($value)
    {
        $temp =  Str::slug($this->attributes['name']).'@tabketna.com';
        $username = $temp;
        $i = 0;
        while(User::whereUsername($username)->exists())
        {
            $i++;
            $username = Str::random(2) .$i.$temp ;
        }
        $this->attributes['username'] = $username;
    }


    public function  ApiCreateToken()
    {
        $this->tokens()->delete();
        $tokenResult = $this->createToken('authToken')->plainTextToken;
        $this->access_token= $tokenResult ;
    }

}
