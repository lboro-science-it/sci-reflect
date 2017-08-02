<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>Groups</h3>
            </div>
            <div class="panel-body">
                @isset($groups)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Group</th>
                                <th>Number</th>
                                <th>{{-- edit --}}</th>
                                <th>{{-- delete --}}</th>
                            </tr>
                        </thead>
                        <tbody>                
                            @foreach($groups as $group)
                                <tr>
                                    <td>{{ $group->name }}</td>
                                    <td>{{ $group->number }}</td>
                                    <td>Edit</td>
                                    <td>Delete</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endisset
            </div>
        </div>
    </div>
</div>