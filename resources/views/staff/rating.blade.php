@extends('layouts.staff')

@section('title')

Rating {{ $student->name }} in {{ $round->title }}

@endsection

@section('content')

    @section('sciReflect')
        sciReflect.skills = {!! json_encode($skills) !!};
        sciReflect.choices = {!! json_encode($choices) !!};
        sciReflect.descriptors = {!! json_encode($descriptors) !!};
    @append
    <student-rater :skills="sciReflect.skills" 
                   :choices="sciReflect.choices"
                   :descriptors="sciReflect.descriptors"
                   student-id="{{ $student->id }}"
                   student-name="{{ $student->name }}"
                   round-number="{{ $round->round_number }}"
                   post-url="{{ url('a/' . $activity->id . '/r/' . $round->round_number . '/rate/' . $student->id) }}"
                   home-url="{{ url('a/' . $activity->id . '/users' ) }}">
    </student-rater>

@endsection