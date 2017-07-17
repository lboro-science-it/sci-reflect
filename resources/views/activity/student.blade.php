@extends('layouts.app')

@section('title')

{{ $activity->name }}

@endsection

@section('content')

@include($activityData->view, ['activityData' => $activityData])

@endsection