@extends('layouts.staff')

@section('title')
    Staff dashboard: {{ $activity->name }}
@endsection

@section('content')

    <div class="panel panel-default">
        <div class="panel-heading">
            @if($activity->status == 'design')
                <h3>Design your activity</h3>
            @else
                <h3>Activity dashboard</h3>
            @endif
        </div>
        <div class="panel-body">
            @if($activity->status == 'design')
                <p>Welcome to sciReflect, a learning tool to help your students reflect on their skills.</p>
                <p>Before you open the activity, here are a few things you might want to do:</p>
                
                <h4>Setup activity</h4>
                <p>Set open and close date for activity. If you set these, then students can't access the activity outside of the dates. 
                    If you don't set them, then access is controlled based on whether the activity is Open or Closed (the blue button on the left).</p>
                <p>Set format. At the time of writing, only Linear format is implemented.</p>
                <p>Set the activity name. By default, this is the same as the moodle activity, but it doesn't have to be.</p>
                
                <h4>Set up rounds</h4>
                <p>A round is a single iteration of the student reflecting against a selection of skills. An activity can have a single round, or multiple.
                    A round can have any or all of the skills present in the activity, so for example the student may reflect against introductory skills in the first round,
                    then bring in more advanced skills in the second round. Rounds contain pages, which contain skills, so you can control the order in which students reflect
                    on skills.</p>
                <p>Each round can have its own format, although it's a moot point while only the linear format is implemented. Linear format just means the skills are listed 
                    on pages, which need to be navigated via next/prev buttons.</p>
                <p>Like activities, rounds can have open and close dates. If these are not set, students will be able to access a round as soon as they have completed its
                    predecessor.</p>
                <p>You can also set rounds to be rateable by staff as well as students.</p>

            @else
                Todo insert info about activity dashboard.
            @endif
        </div>
    </div>

@endsection