<form id="student-upload" action="{{ url('a/' . $activity->id . '/students') }}" method="POST">
    {{ csrf_field() }}
    {{ method_field('POST') }}
    <div class="form-group">
        <textarea name="students" cols="70" rows="10" placeholder="Paste emails of students in this module, on a new line for each."></textarea>
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>
