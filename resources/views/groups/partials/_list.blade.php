@if($groups->count() > 0)
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>Groups</h3>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Group</th>
                                <th>Users in group</th>
                                <th>{{-- edit --}}</th>
                                <th>{{-- delete --}}</th>
                            </tr>
                        </thead>
                        <tbody>                
                            @foreach($groups as $group)
                                <tr is="group-listing" 
                                    name="{{ $group->name }}"
                                    users="{{ $group->getUsers()->count() }}"
                                    id="{{ $group->id }}">
                                    <td>{{ $group->name }}</td>
                                    <td>{{ $group->getUsers()->count() }}</td>
                                    <td>Edit</td>
                                    <td>Delete</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif
