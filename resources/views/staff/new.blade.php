@extends('layouts.staff')

@section('title')

New activity

@endsection

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form id="new-activity-form" action="{{ url('a/' . $activity->id) }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input name="name" type="text" class="form-control" maxlength="255" value="{{ $activity->name }}">

                            <label for="open_date">Open date (leave blank for manual open/close)</label>
                            <input name="open_date" type="datetime-local" class="form-control">

                            <label for="close_date">Close date (leave blank for manual open/close)</label>
                            <input name="close_date" type="datetime-local" class="form-control">

                            <label for="format">Format</label>
                            <select name="format" class="form-control">
                                @foreach($formats as $className => $displayName)
                                <option value="{{ $className }}">{{ $displayName }}</option>
                                @endforeach
                            </select>

                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection