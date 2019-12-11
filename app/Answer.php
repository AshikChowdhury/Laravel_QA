<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * Class Answer
 * @package App
 */
class Answer extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['body', 'user_id'];
    /**
     * @return BelongsTo
     */
    public function question(){
        return $this->belongsTo(Question::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * @return string
     */
    public function getBodyHtmlAttribute(){
        return \Parsedown::instance()->text($this->body);
    }

    /**
     *
     */
    public static function boot()
    {
        parent::boot();

        static::created(function ($answer){
            $answer->question->increment('answers_count');
        });

        static::deleted(function ($answer){
            $question = $answer->question;
            $question->decrement('answers_count');
//            no need if we handle it on database using foreign key
//            if($question->best_answer_id === $answer->id){
//                $question->best_answer_id = NULL;
//                $question->save();
//            }
        });
    }

    /**
     * @return string
     */
    public function getUrlAttribute(){
        return route('questions.show', $this->slug);
    }

    /**
     * @return mixed
     */
    public function getCreatedDateAttribute(){
        return $this->created_at->diffForHumans();
    }

    /**
     * @return string
     */
    public function getStatusAttribute(){
        return $this->isBest() ? 'vote-accepted' : '';
    }


    /**
     * @return bool
     */
    public function getIsBestAttribute(){
        return $this->isBest();
    }

    /**
     * @return bool
     */
    public function isBest(){
        return $this->id === $this->question->best_answer_id;
    }

    /**
     * @return MorphToMany
     */
    public  function votes(){
        return $this->morphToMany(User::class, 'votable')->withTimestamps();
    }

    /**
     * @return MorphToMany
     */
    public function upVotes(){
        return $this->votes()->wherePivot('vote',1);
    }

    /**
     * @return MorphToMany
     */
    public function downVotes(){
        return $this->votes()->wherePivot('vote',-1);
    }

}
