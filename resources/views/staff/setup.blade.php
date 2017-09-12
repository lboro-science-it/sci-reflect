@extends('layouts.staff')

@section('title')

Setup {{ $activity->name }}

@endsection

@section('content')

  @section('sciReflect')

  @append

  <p>todo: insert stuff about setting up the activity, like name, open date, close date, format, number of choices...</p>



    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>Create from JSON</h3>
        </div>
        <div class="panel-body">
            <form id="create-from-json-form" action="{{ url('a/' . $activity->id) . '/create-from-json' }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('POST') }}
                <div class="form-group">

                    <label for="create_from_json">Create content from pasted JSON (optional)</label>
                    <textarea name="create_from_json" rows="15" class="form-control" placeholder="Paste json object, see napkin for details of structure">
                    </textarea>

                    <p>Use this form to create rounds, pages, skills, indicators, choices, categories - all of the content required for the activity - from a bunch of JSON data.</p>
                    <p>Recommend you use https://jsonlint.com/ before putting the json in here, as I don't have time to deal with errors.</p>
                    <p>todo: detail the structure.</p>

                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>










@endsection