@extends('layouts.app')

@section('title')
    Staff dashboard: {{ $activity->name }}
@endsection

@section('content')
    @include('activity.staff.partials._tasks')
    @include('activity.staff.partials._userupload')

@endsection