@extends('layouts.app')

@section('title')
    Staff dashboard: {{ $activity->name }}
@endsection

@section('content')

    @include('activity.partials._openclose')

    @include('activity.partials._studentupload')

    @include('activity.partials._studentlist')

    Todo:<br>

    <li><strike>List students</strike></li>
    <li><strike>List students' current rounds</strike></li>
    <li><strike>List students' percent complete</strike></li>
    <li>Link to view student selections / wheel (if round complete)</li>
    <li>Link to rate students</li>
    <li><strike>List groups</strike></li>
    <li>List students by group</li>

@endsection