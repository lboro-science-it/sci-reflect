@if($activity->status == 'open')
    @include('activity.partials._close')
@else
    @include('activity.partials._open')
@endif