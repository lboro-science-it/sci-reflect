@extends('layouts.app')

@section('title')

Design: {{ $activity->name }}

@endsection

@section('content')

    @include('activity.partials._tasks')
    
    @include('activity.partials._openclose')

    @include('activity.partials._studentupload')

    @include('activity.partials._studentlist')

    Todo: <br>
    <li><strike>Add students (CSV / Paste)</strike></li>
    <li>Add/Delete/Rearrange rounds</li>
    <li>A/D/R skills</li>
    <li>A/D/R indicators</li>
@endsection