@extends('layouts.app')

@section('title')

{{ $activity->name }}

@endsection

@section('content')

<div class="row">

    {{-- Activity format-specific content --}}
    <div class="col-md-5 col-md-offset-2 col-sm-8">
        @include($activityData->view, ['activityData' => $activityData])
    </div>

    {{-- Activity sidebar content (chart, rounds list, quote...) --}}
    <div class="col-md-3 col-sm-4">
        @isset($activityData->chartData)
            <div class="panel panel-default">
                <div class="panel-heading">
                    @if($activityData->rounds->completed->count() > 0)
                        <h3>Your skills after 
                            <a href="{{ url('a/' . $activity->id . '/student/r/' . $activityData->rounds->completed->last()->round_number . '/chart') }}">
                                {{ $activityData->rounds->completed->last()->title }}
                            </a>
                        </h3>
                    @else
                        <h3>Your skills</h3>
                    @endif
                </div>
                <div class="panel-body" style="padding: 20px;">
                    @include('partials.chart.chart', ['chartData' => $activityData->chartData])
                </div>
            </div>
        @endisset

        @include('partials.rounds.completed', ['rounds' => $activityData->rounds->completed])
        @include('partials.rounds.future', ['rounds' => $activityData->rounds->future])
        @isset($knowledgeQuote)
            @include('activity.partials._knowledge_quote', ['knowledgeQuote' => $knowledgeQuote])
        @endisset
    </div>
</div>

@endsection