@extends('layouts.app')

@section('title')

{{ $pageData->roundTitle }}: {{ $pageData->pageTitle }}

@endsection

@section('content')

@include($pageData->view, ['pageData' => $pageData])

@endsection