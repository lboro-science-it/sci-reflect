@extends('layouts.app')

@section('title')

Design: {{ $activity->name }}

@endsection

@section('content')

    @include('activity.staff.partials._tasks')
    
    @include('activity.staff.partials._openclose')

    @include('activity.staff.partials._studentupload')

    @include('activity.staff.partials._studentlist')

    Todo: <br>
    <li><strike>Add students (CSV / Paste)</strike></li>
    <li>Add/Delete/Rearrange rounds</li>
    <li>A/D/R skills</li>
    <li>A/D/R indicators</li>
@endsection