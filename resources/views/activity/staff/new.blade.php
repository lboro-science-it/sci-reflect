@extends('layouts.app')

@section('title')

New activity

@endsection

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    @include('activity.staff.partials._create')
                </div>
            </div>
        </div>
    </div>
</div>

@endsection