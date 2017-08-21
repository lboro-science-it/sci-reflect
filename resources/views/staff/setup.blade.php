@extends('layouts.app')

@section('title')

Setup structure for {{ $activity->name }}

@endsection

@section('content')

  @section('sciReflect')
    sciReflect.rounds = {!! json_encode($rounds) !!};
  @append
  
  <activity-setup :rounds="sciReflect.rounds">
  </activity-setup>

@endsection