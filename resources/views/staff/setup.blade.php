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

                    <button type="submit" class="btn btn-primary">Save</button>

                    <p>Use this form to create rounds, pages, skills, indicators, choices, categories - all of the content required for the activity - from a bunch of JSON data.</p>
                    <p>Recommend you use https://jsonlint.com/ before putting the json in here, as I don't have time to deal with errors.</p>
                    <p>Below JSON demonstrates the format required for the JSON object pasted in. Make sure the JSON syntax is correct (no commas on the last item in arrays for example). Note that ids refer to indexes of items within arrays in the JSON object, NOT ids of stuff in the database. They are transposed when the JSON object is parsed.</p>
                    <p>
                        <code>{<br/>
                        &nbsp;&nbsp;"rounds": [<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"keep_visible": 1,<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"round_number": 1,<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"title": "Round 1",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"block_id": 0,<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"staff_rate": 1,<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"student_rate": 1,<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"pages": [<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;0, 1, 2<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;},<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"keep_visible": 1,<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"round_number": 2,<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"title": "Round 2",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"block_id": 1,<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"staff_rate": 1,<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"student_rate": 1,<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"pages": [<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;0, 1, 3, 2<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;}<br/>
                        &nbsp;&nbsp;],<br/>
                        &nbsp;&nbsp;"pages": [<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"title": "Intro page",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"blocks": [<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"id": 2,<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"position": 1<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;},<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"title": "Basic skills page",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"blocks": [<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"id": 3,<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"position": 1<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;],<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"skills": [<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"id": 0,<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"position": 2<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;},<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"id": 1,<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"position": 3<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;},<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"title": "Outro page",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"blocks": [<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"id": 4,<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"position": 1<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;},<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"title": "Advanced skills page",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"skills": [<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"id": 2,<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"position": 1<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;},<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"id": 3,<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"position": 2<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;}<br/>
                        &nbsp;&nbsp;],<br/>
                        &nbsp;&nbsp;"skills": [<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"title": "Basic skill 1",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"description": "This is the description of a basic skill. This block_id is the content shown when the user clicks the 'improve' link.",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"block_id": 5,<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"number": 1,<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"category_id": 0,<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"indicators": [<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"text": "First indicator for basic skill 1",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"number": 1<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;},<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"text": "Second indicator for basic skill 1",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"number": 2<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;],<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"descriptors": [<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"S1 This is the descriptor for the first choice",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"S1 This is the descriptor for the second choice",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"S1 Descriptor for third choice",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"S1 Descriptor for fourth choice"<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;},<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"title": "Basic skill 2",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"description": "The is the description of another basic skill. Note that it doesn't matter how many indicators there are (as long as there's at least one)",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"block_id": 6,<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"number": 2,<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"category_id": 0,<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"indicators": [<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"text": "First indicator for basic skill 2",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"number": 1<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;},<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"text": "Second indicator for basic skill 2",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"number": 2<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;},<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"text": "Fourth indicator for basic skill 2 - just to demonstrate that it's the number that determines what order they are rendered, not their order in this array.",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"number": 4<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;},<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"text": "Third indicator for basic skill 2",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"number": 3<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;],<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"descriptors": [<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"S2 This is the descriptor for the first choice",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"S2 This is the descriptor for the second choice",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"S2 Descriptor for third choice",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"S2 Descriptor for fourth choice"<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;},<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"title": "Advanced skill 1",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"description": "This is an advanced skill. As you can see, this is only used on the 4th page (advanced skills page) which is only present in round 2.",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"block_id": 7,<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"number": 1,<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"category_id": 1,<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"indicators": [<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"text": "Indicator for advanced skill 1",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"number": 1<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;],<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"descriptors": [<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"S3 This is the descriptor for the first choice",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"S3 This is the descriptor for the second choice",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"S3 Descriptor for third choice",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"S3 Descriptor for fourth choice"<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;},<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"title": "Advanced skill 2",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"description": "A second advanced skill. Note that the advanced skills are in a different category to the basic skills. They'll be displayed with a different colour",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"block_id": 8,<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"number": 2,<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"category_id": 1,<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"indicators": [<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"text": "Indicator for advanced skill 2. Notice numbers of the skills. These determine their render order within their category (which has its own render order)",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"number": 1<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;},<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"text": "Another advanced skill 2 indicator. Nothing else to signpost here.",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"number": 2<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;],<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"descriptors": [<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"S4 This is the descriptor for the first choice",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"S4 This is the descriptor for the second choice",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"S4 Descriptor for third choice",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"S4 Descriptor for fourth choice"<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;]<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;}<br/>
                        &nbsp;&nbsp;],<br/>
                        &nbsp;&nbsp;"categories": [<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"name": "Basic skills category",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"color": "#dff0d8",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"number": 1<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;},<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"name": "Advanced skills category",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"color": "#0f0f04",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"number": 2<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;}<br/>
                        &nbsp;&nbsp;],<br/>
                        &nbsp;&nbsp;"blocks": [<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"content": "Content for the round 1 intro. Notice how the block_ids referred to in this json object are in fact references to the index within this json object - not the database itself. They will be transposed to what the ids become in the database."<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;},<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"content": "Content for the round 2 intro. Of course, array indexes start at 0. Therefore this is block_id 1 as far as the json parser is concerned. When it gets inserted into the database, the actual id may end up as 4025 or something, doesn't matter."<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;},<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"content": "Block for intro page. Notice how the intro page is included in both rounds 1 and 2. That means this content will be seen on the first page of both rounds."<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;},<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"content": "Block for basic skills page. Notice how pages can have both blocks and skills. The position properties are used to sort the order they are rendered on the page."<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;},<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"content": "Outro page content block. Notice how this page is included on both rounds 1 and 2, but in round 2, it is the fourth page. Page numbers and stuff will be automatically dealt with when the object is parsed."<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;},<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"content": "Block for basic skill 1. When the students complete a round and see their skills overview, they can click on each skill and see advice on improving the skill - that is contained in this block."<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;},<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"content": "Block for basic skill 2. Nothing much else to say on the blocks used in skills"<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;},<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"content": "Block for advanced skill 1."<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;},<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"content": "Block for advanced skill 2"<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;}<br/>
                        &nbsp;&nbsp;],<br/>
                        &nbsp;&nbsp;"choices": [<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"label": "Choice 1",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"value": 1<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;},<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"label": "Choice 2",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"value": 2<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;},<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"label": "Choice 3",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"value": 3<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;},<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;{<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"label": "AWESOME!",<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"value": 4<br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;}<br/>
                        &nbsp;&nbsp;]<br/>
                        }
                        </code>
                    </p>
                </div>
            </form>
        </div>
    </div>










@endsection