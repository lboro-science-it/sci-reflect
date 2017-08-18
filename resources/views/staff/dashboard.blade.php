@extends('layouts.app')

@section('title')
    Staff dashboard: {{ $activity->name }}
@endsection

@section('content')

    @include('partials.staff.tasks')

    @if(count($students))
        @section('sciReflect')
            sciReflect.rounds = {!! json_encode($rounds) !!};
            sciReflect.students = {!! json_encode($students) !!};
            sciReflect.groups = {!! json_encode($groups) !!};
        @append
        <student-table :rounds="sciReflect.rounds" 
                       :students="sciReflect.students"
                       :groups="sciReflect.groups"
                       put-url="{{ url('a/' . $activity->id . '/groups/') }}">
        </student-table>
    @endif

    @if(count($staff) > 0)    
        @section('sciReflect')
            sciReflect.staff = {!! json_encode($staff) !!};
        @append
        <staff-table :staff="sciReflect.staff">
        </staff-table>
    @endif

    Todo:<br>

    <li><strike>List students</strike></li>
    <li><strike>List students' current rounds</strike></li>
    <li><strike>List students' percent complete</strike></li>
    <li>Link to view student selections / wheel (if round complete)</li>
    <li><strike>Link to rate students</strike></li>
    <li><strike>List groups</strike></li>
    <li><strike>List students by group</strike></li>

@endsection