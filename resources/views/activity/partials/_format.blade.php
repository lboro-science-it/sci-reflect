<label for="format">Format</label>
<select name="format" class="form-control">
    @foreach($formats as $className => $displayName)
    <option value="{{ $className }}">{{ $displayName }}</option>
    @endforeach
</select>