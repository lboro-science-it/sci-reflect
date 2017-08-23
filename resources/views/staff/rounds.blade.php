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
 
  <rounds-setup :blocks="sciReflect.blocks"
                :pages="sciReflect.pages"
                :rounds="sciReflect.rounds"
                :skills="sciReflect.skills">
  </rounds-setup>

  <p>
    todo:
    <strike>List of existing rounds</strike><br>
    <strike>Add a round</strike><br>
    <strike>Edit a round (title, format, number open/close date, content block, whether to keep visible, who can rate it)</strike><br>
    <strike>Delete a round (should also unrelate all pages, delete any orphaned pages, skills etc)</strike><br>
    Add page(s) to round - show all available pages that aren't already added, enable editing of pages, (adding skills to) etc<br>
    Reorder pages in rounds<br>
    Delete pages from rounds<br>
    Add content to pages (blocks, skills)<br>
    Reorder content in pages<br>
    Delete content from pages<br>
  </p>

@endsection