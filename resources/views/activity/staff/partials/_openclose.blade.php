@if($activity->status == 'open')
    <form id="close-activity" action="{{ url('a/' . $activity->id . '/close') }}" method="POST">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <div class="form-group">
            <button type="submit" class="btn btn-default">Close</button>
        </div>
    </form>
@else
    <form id="open-activity" action="{{ url('a/' . $activity->id . '/open') }}" method="POST">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <div class="form-group">
            <button type="submit" class="btn btn-success">Open</button>
        </div>
    </form>
@endif