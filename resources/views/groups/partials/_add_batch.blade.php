<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>Batch add groups</h3>
            </div>
            <div class="panel-body">
                <form id="group-upload" action="{{ url('a/' . $activity->id . '/add-groups-batch') }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('POST') }}
                    <div class="form-group">
                        <label for="groupPrefix">Prefix:</label>
                        <input type="text" name="groupPrefix" class="form-control" placeholder="Enter group prefix">
                    </div>
                    <div class="form-group">
                        <label for="numberToCreate">Number to create:</label>
                        <input type="number" name="numberToCreate" class="form-control" style="width: 100px;">
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg pull-right">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>