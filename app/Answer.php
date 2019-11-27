<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Answer
 * @package App
 */
class Answer extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question(){
        return $this->belongsTo(Question::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
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
            $answer->question->save();
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

}
