@if(count($students))
    @section('sciReflect')
        sciReflect.rounds = {!! json_encode($rounds) !!};
        sciReflect.students = {!! json_encode($students) !!};
        sciReflect.groups = {!! json_encode($groups) !!};
    @append
    <student-table :rounds="sciReflect.rounds" 
                   :students="sciReflect.students"
                   :groups="sciReflect.groups">
    </student-table>
@endif
