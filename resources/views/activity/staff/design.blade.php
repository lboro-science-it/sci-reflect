@extends('layouts.app')

@section('title')

Design: {{ $activity->name }}

@endsection

@section('content')

todo: add sequence buttons in following format to navigate between design setup etc:<br><br>

Create Activity (ticked) -> Set up activity (ticked) -> Add students (unticked) -> Add groups (unticked) -> Add attributes (unticked) -> Set up structure (unticked) -> Open
    
    @include('activity.partials._openclose')

    @include('activity.partials._studentupload')

    @include('activity.partials._studentlist')

    Todo: <br>
    <li><strike>Add students (CSV / Paste)</strike></li>
    <li>Add/Delete/Rearrange rounds</li>
    <li>A/D/R skills</li>
    <li>A/D/R indicators</li>
@endsection