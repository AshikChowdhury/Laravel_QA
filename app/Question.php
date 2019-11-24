<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        return route('questions.show', $this->id);
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
        if ($this->answers > 0){
            if ($this->best_answer_id){
                return 'answered-accepted';
            }
            return 'answered';
        }
        return 'unanswered';
    }

}
