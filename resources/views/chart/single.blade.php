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
            <h3>Your skills</h3>
            @foreach($skills as $skill)
                @include('skills.partials._skill_horizontal', ['skill' => $skill])
            @endforeach
        @endisset
    </div>
</div>

@endsection