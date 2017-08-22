@extends('layouts.staff')

@section('title')

Setup structure for {{ $activity->name }}

@endsection

@section('content')

  {{-- Register the json-formatted collections passed from the controller
       in the global JS sciReflect object so we can use in Vue components --}}
  @section('sciReflect')
    sciReflect.blocks = {!! $blocks !!};
    sciReflect.categories = {!! $categories !!};
    sciReflect.choices = {!! $choices !!};
    sciReflect.pages = {!! $pages !!};
    sciReflect.rounds = {!! $rounds !!};
    sciReflect.skills = {!! $skills !!};
  @append
  
  {{-- pass the json collections from the controller to the Vue component --}}
  <activity-setup :blocks="sciReflect.blocks"
                  :categories="sciReflect.categories"
                  :choices="sciReflect.choices"
                  :pages="sciReflect.pages"
                  :rounds="sciReflect.rounds"
                  :skills="sciReflect.skills">
  </activity-setup>

@endsection