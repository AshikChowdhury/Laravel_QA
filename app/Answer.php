<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Answer
 * @package App
 */
class Answer extends Model
{
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
            if($question->best_answer_id === $answer->id){
                $question->best_answer_id = NULL;
                $question->save();
            }
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
     * @return mixed
     */
    public function getStatusAttribute(){
        return $this->id === $this->question->best_answer_id ? 'vote-accepted' : '';
    }

}
