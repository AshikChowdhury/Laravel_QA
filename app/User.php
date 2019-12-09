<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use phpDocumentor\Reflection\Type;

/**
 * Class User
 * @package App
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return HasMany
     */
    public function questions(){
        return $this->hasMany(Question::class);
    }

    /**
     * @return string
     */
    public function getUrlAttribute(){
        return '#';
    }

    /**
     * @return HasMany
     */
    public function answers(){
        return $this->hasMany(Answer::class);
    }

    /**
     * @return string
     */
    public function getAvatarAttribute(){
        $email = $this->email;
        $size = 32;
        return "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?s=" . $size;

    }

    /**
     * @return BelongsToMany
     */
    public  function favorites(){
        return $this->belongsToMany(Question::class, 'favorites')->withTimestamps(); //,'user_id','question_id');
    }
}
