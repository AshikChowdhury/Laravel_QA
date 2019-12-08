<?php

namespace App\Policies;

use App\User;
use App\Answer;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class AnswerPolicy
 * @package App\Policies
 */
class AnswerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the answer.
     *
     * @param  \App\User  $user
     * @param  \App\Answer  $answer
     * @return mixed
     */
    public function update(User $user, Answer $answer)
    {
        return $user->id === $answer->user_id;
    }

    /**
     * Determine whether the user can delete the answer.
     *
     * @param  \App\User  $user
     * @param  \App\Answer  $answer
     * @return mixed
     */
    public function delete(User $user, Answer $answer)
    {
        return $user->id === $answer->user_id;
    }

    /**
     * @param User $user
     * @param Answer $answer
     * @return bool
     */
    public function accept(User $user, Answer $answer)
    {
        return $user->id === $answer->question->user_id;
    }

}
