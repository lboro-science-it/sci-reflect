<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>Staff</h3>
            </div>
            <div class="panel-body">
                @if($staff->count())
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Group</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Has accessed activity?</th>
                                <th>Last access</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($staff as $staffMember)
                                <tr>
                                    <td>
                                        @isset($staffMember->group)
                                            {{ $staffMember->group->name }}
                                        @else
                                            No group
                                        @endisset
                                    </td>

                                    <td>{{ $staffMember->name }}</td>
                                    <td>{{ $staffMember->email }}</td>

                                    <?php
                                        $hasAccessedClass = isset($staffMember->pivot->lti_user_id) ? 'success' : '';
                                        $hasAccessedText = isset($staffMember->pivot->lti_user_id) ? 'Yes' : 'No';
                                    ?>
                                    <td class="{{ $hasAccessedClass }}">{{ $hasAccessedText }}</td>

                                    <td>
                                        @isset($staffMember->pivot->lti_user_id)
                                            {{ $staffMember->pivot->updated_at }}
                                        @endisset
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>