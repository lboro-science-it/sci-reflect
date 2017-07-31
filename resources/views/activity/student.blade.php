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
        @include('activity.partials._chart')
        @include('rounds/partials/_completed', ['rounds' => $activityData->rounds->completed])
        @include('rounds/partials/_future', ['rounds' => $activityData->rounds->future])
        @isset($knowledgeQuote)
            @include('activity.partials._knowledge_quote', ['knowledgeQuote' => $knowledgeQuote])
        @endisset
    </div>
</div>

@endsection