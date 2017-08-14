@extends('layouts.app')

@section('title')

Rating {{ $student->name }} in {{ $round->title }}

@endsection

@section('content')

    @section('sciReflect')
        sciReflect.skills = {!! json_encode($skills) !!};
        sciReflect.choices = {!! json_encode($choices) !!};
    @append
    <student-rater :skills="sciReflect.skills" 
                   :choices="sciReflect.choices"
                   student-id="{{ $student->id }}"
                   student-name="{{ $student->name }}"
                   round-number="{{ $round->round_number }}">
    </student-rater>

@endsection