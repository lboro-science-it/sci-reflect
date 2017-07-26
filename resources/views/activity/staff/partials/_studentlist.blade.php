@if($students->count())
    <table class="table">
        <thead>
            <tr>
                <th>Group</th>
                <th>Name</th>
                <th>Email</th>
                <th>Current round</th>
                @foreach($rounds as $round)
                    <th>{{ $round->title }}</th>
                @endforeach
                <th>Has accessed activity?</th>
                <th>Has completed activity?</th>
                <th>Last access</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                <tr>
                    <td>
                        @isset($student->group)
                            {{ $student->group->name }}
                        @else
                            No group
                        @endisset
                    </td>

                    <td>{{ $student->name }}</td>
                    <td>{{ $student->email }}</td>

                    <?php
                        $currentRoundNumber = $student->pivot->current_round;
                        $currentRoundClass = isset($currentRoundNumber) ? '' : 'success';
                        $currentRoundText = isset($currentRoundNumber) ? $rounds->where('round_number', $currentRoundNumber)->first()->title : 'Complete';
                    ?>

                    <td class="{{ $currentRoundClass }}">{{ $currentRoundText }}</td>
                    
                    @foreach($rounds as $round)
                        <?php
                            $class = ($student->getCompletion($round) == '100%') ? 'success' : '';
                        ?>
                        <td class="{{ $class }}">{{ $student->getCompletion($round) }}</td>
                    @endforeach

                    <?php
                        $hasAccessedClass = isset($student->pivot->lti_user_id) ? 'success' : '';
                        $hasAccessedText = isset($student->pivot->lti_user_id) ? 'Yes' : 'No';
                    ?>
                    <td class="{{ $hasAccessedClass }}">{{ $hasAccessedText }}</td>

                    <?php
                        $completeClass = ($student->pivot->complete) ? 'success' : '';
                        $completeText = ($student->pivot->complete) ? 'Yes' : 'No';
                    ?>
                    <td class="{{ $completeClass }}">{{ $completeText }}</td>

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