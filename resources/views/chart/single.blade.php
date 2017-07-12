@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-body">
                @include('chart.partials._chart', ['chartData' => $chartData])
            </div>
        </div>
    </div>

    <div class="col-md-6">
        @isset($skills)
            <h3>Your best skills</h3>
            @foreach($skills as $skill)
                @include('skills.partials._skill', ['skill' => $skill])
            @endforeach
        @endisset
        @isset($weakestSkills)
            <h3>Areas for improvement</h3>
            @foreach($weakestSkills as $skill)
                @include('skills.partials._skill', ['skill' => $skill])
            @endforeach
        @endisset
    </div>
</div>

@endsection