<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

/**
 * Class Question
 * @package App
 */
class Question extends Model
{
    /**
     * @var array
     */
    protected  $fillable = ['title','body'];

    /**
     * @return BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * @param $value
     */
    public function setTitleAttribute($value){
        $this->attributes['title'] = $value;
//        $this->attributes['slug'] = Str::slug($value);
//        create slug for bengla
        $this->attributes['slug'] = preg_replace('/\s+/u', '-', trim($value));
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
        if ($this->answers_count > 0){
            if ($this->best_answer_id){
                return 'answered-accepted';
            }
            return 'answered';
        }
        return 'unanswered';
    }

    /**
     * @return string
     */
    public function getBodyHtmlAttribute(){
        return \Parsedown::instance()->text($this->body);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers(){
        return $this->hasMany(Answer::class);
    }

    /**
     * @param Answer $answer
     */
    public function acceptBestAnswer(Answer $answer){
        $this->best_answer_id = $answer->id;
        $this->save();
    }

    /**
     * @return BelongsToMany
     */
    public  function favorites(){
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps(); //'question_id','user_id'
    }

    /**
     * @return mixed
     */
    public function isFavorited(){
        return $this->favorites()->where('user_id', auth()->id())->count();
    }

    /**
     * @return mixed
     */
    public function getIsFavoritedAttribute(){
        return $this->isFavorited();
    }

    /**
     * @return mixed
     */
    public function getFavoritesCountAttribute(){
        return $this->favorites->count();
    }

}
