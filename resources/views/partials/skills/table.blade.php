<table class="table" style="table-layout: fixed;">
    <tbody>
        <tr>
            <th width="50%">
                <h2>{{ $skill->title }}</h2>
            </th>
        <?php $choiceCount = count($choices); ?>
        @foreach($choices as $choice)
            <th class="text-center" width="{{ 50 / $choiceCount }}%">
                <h4>{{ $choice->value }}</h4>
                <small>{{ $choice->label }}</small>
            </th>
        @endforeach
        </tr>
        @foreach($skill->indicators as $indicator)
        <tr>
            <td width="50%">
                {{ $indicator->text }}
            </td>
            @foreach($choices as $choice)
            <td class="text-center" width="{{ 50 / $choiceCount }}%">
                <label style="width: 100%; height: 100%; position: relative;" for="{{ $indicator->id . '-' . $choice->id }}">
                    <input id="{{ $indicator->id . '-' . $choice->id }}" 
                           name="{{ $indicator->id }}" 
                           value="{{ $choice->id }}"
                           class="scireflect-radio"
                           type="radio" 
                           @if($choice->id == $selections[$indicator->id])
                                checked 
                           @endif>
                    <?php
                        $descriptor = $descriptors->where('skill_id', $skill->id)->where('choice_id', $choice->id)->first();
                        $text = isset($descriptor) ? $descriptor->text : null;
                    ?>
                    @isset($text)
                        <p class="scireflect-tooltip">
                            {{ $text }}
                        </p>
                    @endisset
                </label>
            </td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>
