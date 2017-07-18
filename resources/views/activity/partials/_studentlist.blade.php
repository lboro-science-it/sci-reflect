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

                    @isset($student->pivot->current_round)
                        <?php
                            $currentRoundClass = '';
                            $currentRoundText = $student->pivot->current_round;
                        ?>
                    @else
                        <?php
                            $currentRoundClass = 'success';
                            $currentRoundText = 'Complete'
                        ?>
                    @endisset
                    <td class="{{ $currentRoundClass }}">{{ $currentRoundText }}</td>
                    
                    @foreach($rounds as $round)
                        <?php
                            $class = '';
                            if ($student->getCompletion($round) == '100%') {
                                $class = 'success';
                            }
                        ?>
                        <td class="{{ $class }}">{{ $student->getCompletion($round) }}</td>
                    @endforeach

                    @isset($student->pivot->lti_user_id)
                        <?php
                            $hasAccessedClass = 'success';
                            $hasAccessedText = 'Yes';
                        ?>
                    @else
                        <?php
                            $hasAccessedClass = '';
                            $hasAccessedText = 'No';
                        ?>
                    @endisset
                    <td class="{{ $hasAccessedClass }}">{{ $hasAccessedText }}</td>

                    @if($student->pivot->complete)
                        <?php
                            $completeClass = 'success';
                            $completeText = 'Yes';
                        ?>
                    @else
                        <?php
                            $completeClass = '';
                            $completeText = 'No';
                        ?>
                    @endif
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