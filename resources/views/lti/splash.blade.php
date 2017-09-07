@extends('layouts.student')

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
            <div class="col-md-6 col-md-offset-3 text-center">
                <form action="{{ url('launch') }}" method="POST">
                    @foreach($params as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <input type="submit" value="Launch sciReflect" class="btn btn-info btn-lg">
                </form>
            </div>
        </div>

    </div>

</div>


@endsection