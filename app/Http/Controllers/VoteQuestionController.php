<?php

namespace App\Http\Controllers;

use App\Question;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Integer;

/**
 * Class VoteQuestionController
 * @package App\Http\Controllers
 */
class VoteQuestionController extends Controller
{

    /**
     * VoteQuestionController constructor.
     */
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * @param Question $question
     * @return RedirectResponse
     */
    public function __invoke(Question $question)
    {
        $vote = (int) request()->vote;

        auth()->user()->voteQuestion($question, $vote);

        return back();
    }
}
