@extends('layouts.app')

@section('title')

{{ $round->title }}: Review your skills

@endsection

@section('content')

<div class="row">
    <div class="col-md-5 col-md-offset-2">

    <?php
        // doing: only foreach the categories if they are set
        // otherwise just display the skills in sequence as they are gonna be sorted
    ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>
                    @if($categories->count() > 0)
                        Your skills
                    @else
                        Your strongest skills
                    @endif
                </h3>
            </div>
            <div class="panel-body">

                @if($categories->count() > 0)
                    @foreach($categories as $category)
                        <h3>{{ $category->name }}</h3>
                        @foreach($skills->where('category_id', $category->id) as $skill)
                            <div class="row">
                                @include('skills.partials._horizontal', ['skill' => $skill, 'category' => $category, 'improve' => true])
                            </div>
                        @endforeach            
                    @endforeach
                @else
                    @foreach($skills as $skill)
                        <div class="row">
                            @include ('skills.partials._horizontal', ['skill' => $skill, 'category' => $skill->category, 'improve' => true])
                        </div>
                    @endforeach
                @endif

            </div>
        </div>

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

        @include('rounds.partials._completed', ['rounds' => $rounds->completed->whereNotIn('id', $round->id)])

    </div>
</div>

@endsection