<ul class="task-list">
    <li class="task-list-item">
        <div class="task-circle task-circle-done">
            <span class="glyphicon glyphicon-ok"></span>
        </div>
        <div class="task-label">
            Create activity
        </div>
    </li>

    <a href="{{ url('a/' . $activity->id . '/setup') }}">
        <li class="task-list-item">
            <div class="task-circle">
                <span class="glyphicon glyphicon-cog"></span>
            </div>
            <div class="task-label">
                Setup activity
            </div>
        </li>
    </a>

    <a href="{{ url('a/' . $activity->id . '/add-students') }}">
        <li class="task-list-item">
            <div class="task-circle">
                <span class="glyphicon glyphicon-user"></span>
            </div>
            <div class="task-label">
                Add students / staff / groups (optional)
            </div>
        </li>
    </a>

    <li class="task-list-item">
        <div class="task-circle">
            <span class="glyphicon glyphicon-plus"></span>
        </div>
        <div class="task-label">
            Add skills
        </div>
    </li>

    <li class="task-list-item">
        <div class="task-circle">
            <span class="glyphicon glyphicon-tasks"></span>
        </div>
        <div class="task-label">
            Create structure
        </div>
    </li>

    <li class="task-list-item">
        <div class="task-circle">
            <span class="glyphicon glyphicon-off"></span>
        </div>
        <div class="task-label">
            Open activity (if necessary actions done)
        </div>
    </li>
</ul>