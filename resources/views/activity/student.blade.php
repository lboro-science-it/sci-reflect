@extends('layouts.app')

@section('title')

{{ $activity->name }}

@endsection

@section('content')

@include($activityData->activityView, ['activityData' => $activityData])

@endsection