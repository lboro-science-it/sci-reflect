@extends('layouts.app')

@section('title')
    Manage groups: {{ $activity->name }}
@endsection

@section('content')

@include('activity.staff.partials._tasks')

@include('groups.partials._add_bulk')
@include('groups.partials._add_batch')

@isset($groups)
    @foreach($groups as $group)
        {{ $group->name }}, 
    @endforeach
@endisset


    <h4>todo</h4>
    <li><strike>form for adding groups</strike></li>
    <li>display existing groups</li>
    <li>checkbox for deleting groups</li>
    <li>form for editing group name</li>
    <li>consider when deleting what happens to any users related to said group</li>
@endsection