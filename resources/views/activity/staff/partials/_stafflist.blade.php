@if(count($staff) > 0)    
    @section('sciReflect')
        sciReflect.staff = {!! json_encode($staff) !!};
    @append
    <staff-table :staff="sciReflect.staff">
    </staff-table>
@endif
