<form id="open-activity" action="{{ url('a/' . $activity->id . '/open') }}" method="POST">
    {{ csrf_field() }}
    {{ method_field('PUT') }}
    <div class="form-group">
        <button type="submit" class="btn btn-success">Open</button>
    </div>
</form>