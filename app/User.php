<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
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

    /**
     * @return MorphToMany
     */
    public  function voteQuestions(){
        return $this->morphedByMany(Question::class, 'votable')->withTimestamps();
    }

    /**
     * @return MorphToMany
     */
    public  function voteAnswers(){
        return $this->morphedByMany(Answer::class, 'votable')->withTimestamps();
    }

    public function voteQuestion(Question $question, $vote){
        $voteQuestions = $this->voteQuestions();
        if ($voteQuestions->where('votable_id', $question->id)->exists()){
            $voteQuestions->updateExistingPivot($question, ['vote' => $vote]);
        }
        else{
            $voteQuestions->attach($question, ['vote' => $vote]);
        }

        $question->load('votes');
        $upVotes = (int) $question->upVotes()->sum('vote');
        $downVotes = (int) $question->downVotes()->sum('vote');

        $question->votes_count = $upVotes + $downVotes;
        $question->save();

    }
}


