@extends('layouts.app')

@section('title')

Setup structure for {{ $activity->name }}

@endsection

@section('content')

  @include('partials.staff.tasks')

  @section('sciReflect')
    sciReflect.rounds = {!! $rounds !!};
    sciReflect.pages = {!! $pages !!};
    sciReflect.skills = {!! $skills !!};
    sciReflect.blocks = {!! $blocks !!};
  @append
  
  <activity-setup :rounds="sciReflect.rounds">
  </activity-setup>

@endsection