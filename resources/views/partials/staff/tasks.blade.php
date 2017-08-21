<h3>Tasks</h3>
<p>** will style this as icons once structure complete **</p>
<a href="{{ url('a/' . $activity->id) }}">
    Dashboard
</a>

|

{{-- Setup activity (rounds, pages, etc --}}
@if($activity->status == 'design')
<a href="{{ url('a/' . $activity->id . '/setup') }}">
@endif
    Setup activity
@if($activity->status == 'design')
</a>
@endif

|

{{-- Add users --}}
<a href="{{ url('a/' . $activity->id . '/users/add') }}">
   Add users
</a>

|

{{-- Manage groups --}}
<a href="{{ url('a/' . $activity->id . '/groups') }}">
    Manage groups
</a>

|

Add skills: ** todo: only show if $activity->status == 'design' **
Create structure: ** todo: only show if $activity->status == 'design' **
Open activity: @include('partials.staff.openclose')

{{-- do this once all tasks are confirmed
<div class="row">
    <div class="col-md-1 col-md-offset-3 col-sm-2 col-xs-4 text-center task-col">
        <div class="task-circle task-circle-done">
            <span class="glyphicon glyphicon-ok"></span>
        </div>
        <div class="task-label">
            Create activity
        </div>
    </div>
    <div class="col-md-1 col-sm-2 col-xs-4 text-center task-col">
        <div class="task-circle">
            <span class="glyphicon glyphicon-cog"></span>
        </div>
        <div class="task-label">
            Setup activity
        </div>
    </div>
    <a href="{{ url('a/' . $activity->id . '/users/add') }}">
        <div class="col-md-1 col-sm-2 col-xs-4 text-center task-col">
            <div class="task-circle">
                <span class="glyphicon glyphicon-user"></span>
            </div>
            <div class="task-label">
                Add users
            </div>
        </div>
    </a>
    <div class="col-md-1 col-sm-2 col-xs-4 text-center task-col">
        <div class="task-circle">
            <span class="glyphicon glyphicon-plus"></span>
        </div>
        <div class="task-label">
            Add skills
        </div>
    </div>
    <div class="col-md-1 col-sm-2 col-xs-4 text-center task-col">
        <div class="task-circle">
            <span class="glyphicon glyphicon-tasks"></span>
        </div>
        <div class="task-label">
            Create structure
        </div>
    </div>
    <div class="col-md-1 col-sm-2 col-xs-4 text-center task-col">
        <div class="task-circle">
            <span class="glyphicon glyphicon-off"></span>
        </div>
        <div class="task-label">
            Open activity (if necessary actions done)
        </div>
    </div>

</div>

--}}