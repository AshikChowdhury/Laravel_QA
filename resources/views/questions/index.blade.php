@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">All Questions</div>

                    <div class="card-body">
                       @foreach($questions as $question)
                            <div class="media">

                            </div>
                       @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
