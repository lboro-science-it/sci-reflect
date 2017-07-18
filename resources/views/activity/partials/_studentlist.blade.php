@if($students->count())
    <table class="table">
        <thead>
            <tr>
                <th>Group</th>
                <th>Name</th>
                <th>Email</th>
                <th>Current round</th>
                <th>% complete (round)</th>
                <th>Has accessed activity?</th>
                <th>Last access</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                <tr>
                    <td>** todo insert group **</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->pivot->current_round }}</td>
                    <td>{{ $student->completion }}</td>
                    <td>
                        @isset($student->pivot->lti_user_id)
                            Yes
                        @else
                            No
                        @endisset
                    </td>
                    <td>
                        @isset($student->pivot->lti_user_id)
                            {{ $student->pivot->updated_at }}
                        @endisset
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif