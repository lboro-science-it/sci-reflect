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

                            @if($activities->count())
                            <label for="clone_from">Clone content from (optional)</label>
                            <select name="clone_from" class="form-control">
                                <option value="null">Don't clone any content</option>
                                @foreach($activities as $activity)
                                <option value="{{ $activity->id }}">{{ $activity->name }} ({{ $activity->rounds->count() }} rounds, {{ $activity->skills->count() }} skills)</option>
                                @endforeach
                            </select>

                            <p>Warning: cloning content from another activity may take a while, as it has to create a whole bunch of database records and transpose all of their relationships. That's potentially a lot of queries! Be patient!</p>
                            @endif

                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection