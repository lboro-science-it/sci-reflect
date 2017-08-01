@extends('layouts.app')

@section('navbar-header-class')
navbar-transparent
@endsection

@section('title')

@isset($params['custom_title'])
{{ $params['custom_title'] }}
@endisset

@endsection

@section('content')

{{-- parent is full height --}}

<div class="splash-container">

    <div class="splash-centre">
        @isset($params['custom_welcome'])
            <div class="row">
                <div class="col-md-10 col-md-offset-2">
                    {{ $params['custom_welcome'] }}
                </div>
            </div>
        @endisset
        <div class="row">
            <div class="col-md-3 col-md-offset-2">
                ** insert image **
            </div>
            <div class="col-md-5 text-center">
                <h1>Some tagline about reflecting. Word.</h1>
                <form action="{{ url('launch') }}" method="POST">
                    @foreach($params as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <input type="submit" class="btn btn-info btn-lg">
                </form>
            </div>
        </div>


        <div class="row">
            <div class="col-md-10 col-md-offset-2">
                ** insert instructions for cookies - setting sci-el.lboro.ac.uk as trusted **
            </div>
        </div>

    </div>

</div>


@endsection