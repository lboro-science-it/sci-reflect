@extends('layouts.app')

@section('title')

{{ $activity->name }}

@endsection

@section('content')

Staff view of activity.

@include('activity.partials._openclose')

Todo:<br>
List students | List students' current rounds | List students' percent complete | Link to view student responses | Link to rate students

@endsection