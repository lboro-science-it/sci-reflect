@if($groups->count() > 0)
    @section('sciReflect')
        sciReflect.groups = {!! $groups !!};
    @append
    <group-table :groups="sciReflect.groups"></group-table>
@endif
