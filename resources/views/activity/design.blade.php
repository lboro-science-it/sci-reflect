@extends('layouts.app')

@section('content')
    
    Design view.

    @include('activity.partials._openclose')

    @include('activity.partials._studentupload')

    Todo: <br>
    <li>Add students (CSV / Paste)</li>
    <li>Add/Delete/Rearrange rounds</li>
    <li>A/D/R skills</li>
    <li>A/D/R indicators</li>
@endsection