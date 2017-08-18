<polar-chart :backgrounds="{{ json_encode($chartData->backgrounds) }}"
             :labels="{{ json_encode($chartData->labels) }}" 
             :max="{{ $chartData->max }}"
             :values="{{ json_encode($chartData->values) }}"
             :enabled="{{ json_encode($chartData->enabled) }}">
</polar-chart>