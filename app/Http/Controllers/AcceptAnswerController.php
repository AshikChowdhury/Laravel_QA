<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Answer;

class AcceptAnswerController extends Controller
{
    public function __invoke(Answer $answer)
    {
        try {
            $this->authorize('accept', $answer);
        } catch (AuthorizationException $e) {
        }
        $answer->question->acceptBestAnswer($answer);
        return back();
    }
}
