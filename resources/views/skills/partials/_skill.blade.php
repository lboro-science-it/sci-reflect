{{ $skill->title }}
<br>

<div class="progress">
    <div class="progress-bar" 
         role="progressbar" 
         aria-valuenow="{{ $skill->rating }}" 
         aria-valuemin="0" 
         aria-valuemax="{{ $skill->max }}"
         style="width: {{ $skill->rating / $skill->max * 100 }}%;
                background-color: {{ $skill->category->color }};">
        {{ $skill->rating }}
    </div>
</div>