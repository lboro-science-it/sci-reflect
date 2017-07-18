@extends('layouts.app')

@section('title')

{{ $activity->name }}

@endsection

@section('content')

Staff view of activity.

@include('activity.partials._openclose')

@include('activity.partials._studentlist')

Todo:<br>

<li><strike>List students</strike></li>
<li><strike>List students' current rounds</strike></li>
<li>List students' percent complete</li>
<li>Link to view student responses</li>
<li>Link to rate students</li>
<li>List groups</li>
<li>List students by group</li>

@endsection