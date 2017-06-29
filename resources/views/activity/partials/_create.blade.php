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
            <option value="linear" selected>Linear</option>
            <option value="nonlinear">Nonlinear</option>
        </select>

        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>