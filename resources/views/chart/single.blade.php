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
        <h3>Your best skills</h3>
        <h3>Areas for improvement</h3>
    </div>
</div>

@endsection