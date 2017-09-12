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
                        {
                            "rounds": [
                                {
                                    "keep_visible": 1,
                                    "open_date": "Y-m-d H:i:s",
                                    "close_date": "Y-m-d H:i:s",
                                    "round_number": 1,
                                    "title": "Round 1",
                                    "block_id": 0,
                                    "staff_rate": 1,
                                    "student_rate": 1,
                                    "pages": [
                                        0, 5, 2
                                    ]
                                },
                                {
                                    "keep_visible": 1,
                                    "open_date": "Y-m-d H:i:s",
                                    "close_date": "Y-m-d H:i:s",
                                    "round_number": 2,
                                    "title": "Round 2",
                                    "block_id": 0,
                                    "staff_rate": 1,
                                    "student_rate": 1,
                                    "pages": [
                                        0, 3, 4
                                    ]
                                }
                            ],
                            "pages": [
                                {
                                    "title": "Page 1",
                                    "skills": [
                                        0, 2, 3
                                    ]
                                },
                                {
                                    "title": "Page 2",
                                    "blocks": [
                                        0, 1
                                    ]
                                }
                            ],
                            "skills": [
                                {
                                    "title": "Skill title",
                                    "description": "Skill description",
                                    "block_id": 1,
                                    "number": 1,
                                    "category_id": 0
                                }
                            ],
                            "categories": [
                                {
                                    "name": "Category 1",
                                    "color": "#dff0d8",
                                    "number": 1
                                }
                            ],
                            "blocks": [
                                {
                                    "content": "Block content"
                                }
                            ],
                            "choices": [
                                {
                                    "label": "Choice 1",
                                    "value": 1
                                }
                            ]
                        }
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