<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>Add groups</h3>
            </div>
            <div class="panel-body">
                <form id="group-upload" action="{{ url('a/' . $activity->id . '/groups/bulk') }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('POST') }}
                    <div class="form-group">
                        <textarea name="groups" style="width: 100%;" rows="5" placeholder="Add a bunch of groups to the activity by typing here, on a new line for each."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg pull-right">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>