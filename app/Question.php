<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
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
    use VotableTrait;
    /**
     * @var array
     */
    protected  $fillable = ['title','body'];

    protected  $appends = ['created_date'];

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
        return $this->bodyHtml();
    }

    /**
     * @return HasMany
     */
    public function answers(){
        return $this->hasMany(Answer::class)->orderBy('votes_count','DESC');
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

    /**
     * @return string
     */
    public function getExcerptAttribute(){
       return $this->excerpt(250);
    }

    /**
     * @param $length
     * @return string
     */
    public function excerpt($length){
        return Str::limit(strip_tags($this->bodyHtml()), $length);
    }

    /**
     * @return string
     */
    private function bodyHtml(){
        return \Parsedown::instance()->text($this->body);
    }
}


