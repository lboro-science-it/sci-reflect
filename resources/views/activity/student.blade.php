@extends('layouts.app')

@section('title')

{{ $activity->name }}

@endsection

@section('content')

<div class="row">
    <div class="col-md-5 col-md-offset-2">
        @include($activityData->view, ['activityData' => $activityData])
    </div>
    <div class="col-md-3">
        @include('activity.partials._chart')
        @include('rounds/partials/_completed', ['rounds' => $activityData->rounds->completed])
        @include('rounds/partials/_future', ['rounds' => $activityData->rounds->future])
        @isset($knowledgeQuote)
            @include('activity.partials._knowledge_quote', ['knowledgeQuote' => $knowledgeQuote])
        @endisset
    </div>
</div>

@endsection