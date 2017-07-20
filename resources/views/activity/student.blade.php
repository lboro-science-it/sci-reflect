@extends('layouts.app')

@section('title')

{{ $activity->name }}

@endsection

@section('content')

<div class="row">
    <div class="col-md-2">
        @include('activity.partials._sidebar', ['rounds' => $activityData->rounds])
    </div>
    <div class="col-md-7">
        @include($activityData->view, ['activityData' => $activityData])
    </div>
    <div class="col-md-3">
        @include('activity.partials._chart')
    </div>
</div>

@endsection