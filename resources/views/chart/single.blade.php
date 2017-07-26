@extends('layouts.app')

@section('title')

{{ $round->title }}: Review your skills

@endsection

@section('content')

<div class="row">
    <div class="col-md-5 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-body">
                <h3>Your skills</h3>
                @foreach($skills as $skill)
                <div class="row">
                    @include('skills.partials._horizontal', ['skill' => $skill, 'improve' => true])
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-body" style="padding: 20px;">
                <h3>Your skills after {{ $round->title }}</h3>
                @include('chart.partials._chart', ['chartData' => $chartData])
            </div>
        </div>
    </div>
</div>

@endsection