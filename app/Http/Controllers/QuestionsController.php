<?php

namespace App\Http\Controllers;

use App\Http\Requests\AskQuestionRequest;
use App\Question;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class QuestionsController extends Controller
{
    /**
     * QuestionsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index','show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Factory|View
     */
    public function index()
    {
        $questions = Question::with('user')->latest()->paginate(5);

        return  view('questions.index', compact('questions', $questions));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|View
     */
    public function create()
    {
        $question = new Question();
        return view('questions.create',compact('question'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AskQuestionRequest $request
     * @return RedirectResponse
     */
    public function store(AskQuestionRequest $request)
    {
        $request->user()->questions()->create($request->only('title','body'));

        return  redirect()->route('questions.index')->with('success', 'Your Question Has Been Asked');
    }

    /**
     * Display the specified resource.
     *
     * @param Question $question
     * @return Response
     */
    public function show(Question $question)
    {
        $question->increment('views');
        return view('questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Question $question
     * @return Response
     * @throws AuthorizationException
     */
    public function edit(Question $question)
    {
//        if (Gate::denies('update-question', $question)){
//            abort(403, 'Access Denied');
//        }
        $this->authorize('update', $question);

        return view('questions.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AskQuestionRequest $request
     * @param Question $question
     * @return Response
     * @throws AuthorizationException
     */
    public function update(AskQuestionRequest $request, Question $question)
    {
//        if (Gate::denies('update-question', $question)){
//            abort(403, 'Access Denied');
//        }
        $this->authorize('update', $question);
        $question->update($request->only('title','body'));

        return redirect('/questions')->with('success','Your question has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Question $question
     * @return Response
     * @throws \Exception
     */
    public function destroy(Question $question)
    {
//        if (Gate::denies('delete-question', $question)){
//            abort(403, 'Access Denied');
//        }
        $this->authorize("delete", $question);
        $question->delete();
        return redirect('/questions')->with('success', 'Question has been deleted.');
    }
}
