@extends('layouts.app')

@section('title')
    Manage groups: {{ $activity->name }}
@endsection

@section('content')
    <h4>todo</h4>
    <li>form for adding groups</li>
    <li>display existing groups</li>
    <li>checkbox for deleting groups</li>
    <li>form for editing group name</li>
    <li>consider when deleting what happens to any users related to said group</li>
@endsection