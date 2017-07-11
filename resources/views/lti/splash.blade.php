@extends('layouts.app')

@section('title')

@isset($params['custom_title'])

@endisset

@endsection

@section('content')

@isset($params['custom_welcome'])
    <p>
    {{ $params['custom_welcome'] }}
    </p>
@endisset

Splash screen to ensure the user creates a cookie / initiates session.<br><br>
** insert control for cookie-less mode + instructions for setting sci-el.lboro.ac.uk as a trusted site **
<form action="{{ url('launch') }}" method="POST">
    @foreach($params as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach
    <input type="submit">
</form>

@endsection