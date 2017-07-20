<form id="staff-upload" action="{{ url('a/' . $activity->id . '/staff') }}" method="POST">
    {{ csrf_field() }}
    {{ method_field('POST') }}
    <div class="form-group">
        <textarea name="staff" cols="70" rows="10" placeholder="Paste emails of staff in this module, on a new line for each.">

        </textarea>
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>