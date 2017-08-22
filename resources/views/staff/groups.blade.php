@extends('layouts.staff')

@section('title')
    Manage groups: {{ $activity->name }}
@endsection

@section('content')

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

    @section('sciReflect')
        sciReflect.groups = {!! json_encode($groups) !!};
    @append

    <group-table :groups="sciReflect.groups"
                 delete-url="{{ url('a/' . $activity->id . '/groups/') }}"
                 put-url="{{ url('a/' . $activity->id . '/groups/') }}">
    </group-table>

    <group-bulk post-url="{{ url('a/' . $activity->id . '/groups/bulk') }}">
    </group-bulk>
    
    <group-batch post-url="{{ url('a/' . $activity->id . '/groups/batch') }}">
    </group-batch>

@endsection