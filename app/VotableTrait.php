<?php
namespace App;

/**
 * Trait VotableTrait
 * @package App
 */
trait VotableTrait{

    /**
     * @return mixed
     */
    public  function votes(){
        return $this->morphToMany(User::class, 'votable')->withTimestamps();
    }

    /**
     * @return mixed
     */
    public function upVotes(){
        return $this->votes()->wherePivot('vote',1);
    }

    /**
     * @return mixed
     */
    public function downVotes(){
        return $this->votes()->wherePivot('vote',-1);
    }
}
