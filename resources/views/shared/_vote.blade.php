@if($model instanceof App\Question)
    @php
        $name = 'question';
        $firestURISegment = 'questions';
    @endphp
@elseif($model instanceof App\Answer)
    @php
        $name = 'answer';
        $firestURISegment = 'answers';
    @endphp
@endif

<div class="d-fex flex-column vote-controls">
    <a title="This {{$name}} is useful" class="vote-up {{ Auth::guest() ? 'off' : '' }}"
       onclick="event.preventDefault(); document.getElementById('up-vote-{{ $formId }}').submit()"
    >
        <i class="fas fa-caret-up fa-3x"></i>
    </a>
    <form action="/{{ $firestURISegment }}/{{ $model->id }}/vote" id="up-vote-{{ $fromId }}"  method="POST" style="display: none">
        @csrf
        <input type="hidden" name="vote" value="1">
    </form>
    <span class="vote-counts">{{ $model->votes_count }}</span>
    <a title="This {{$name}} is not useful" class="vote-down {{ Auth::guest() ? 'off' : '' }}"
       onclick="event.preventDefault(); document.getElementById('down-vote-{{ $fromId }}').submit()"
    >
        <i class="fas fa-caret-down fa-3x"></i>
    </a>
    <form action="/{{ $firestURISegment }}/{{ $model->id }}/vote" id="down-vote-{{ $fromId }}"  method="POST" style="display: none">
        @csrf
        <input type="hidden" name="vote" value="-1">
    </form>
    @if($model instanceof App\Question)
        @include('shared._favorite', ['model' => $model])
    @elseif($model instanceof App\Answer)
        @include('shared._accept', ['model' => $model])
    @endif
</div>
