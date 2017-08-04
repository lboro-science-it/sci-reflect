@extends('layouts.app')

@section('title')
    Manage groups: {{ $activity->name }}
@endsection

@section('content')

@include('activity.staff.partials._tasks')

<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-body">
                <p>
                    This page allows you to add, edit, delete and view groups. 
                    Add students and staff to groups via the home page.
                    If staff are members of a group, they will only have access to students in that group.
                    If students are members of a group, they will be able to see which staff members are in that group.
                </p>
            </div>
        </div>
    </div>
</div>

@include('groups.partials._list')
@include('groups.partials._add_bulk')
@include('groups.partials._add_batch')

    <h4>todo</h4>
    <li><strike>form for adding groups</strike></li>
    <li>display existing groups</li>
    <li>checkbox for deleting groups</li>
    <li>form for editing group name</li>
    <li>consider when deleting what happens to any users related to said group</li>
@endsection