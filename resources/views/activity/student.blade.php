@extends('layouts.app')

@section('title')

{{ $activity->name }}

@endsection

@section('content')

Student view. Todo: <br>
Explanatory Text | Current Round | Current Round % Complete | Previous Rounds | Forthcoming Rounds | Current Skills | Strongest Skills | Skills to Improve<br>

@include('activity.partials._resumelink')

@endsection