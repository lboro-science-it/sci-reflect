@extends('layouts.app')

@section('title')

{{ $round->title }}: Review your skills

@endsection

@section('content')

<div class="row">
    <div class="col-md-5 col-md-offset-2">

    @foreach($categories as $category)
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>{{ $category->name }}</h3>
            </div>
            <div class="panel-body">
                @foreach($category->skills as $skill)
                <div class="row">
                    @include('skills.partials._horizontal', ['skill' => $skill, 'category' => $category, 'improve' => true])
                </div>
                @endforeach
            </div>
        </div>                    
    @endforeach

    </div>
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>Your skills after {{ $round->title }}</h3>
            </div>
            <div class="panel-body" style="padding: 20px;">
                @include('chart.partials._chart', ['chartData' => $chartData])
            </div>
        </div>
    </div>
</div>

@endsection