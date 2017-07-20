<div class="row">
    @foreach($activityData->categories as $category)
    <div class="col-sm-4">
        <div class="panel panel-default">
            <div class="panel-body">
                <img src="{{ $category->icon_url }}">
                <h4>{{ $category->name }}</h4>
                <p>Total skills: {{ $category->totalSkills }}</p>
                <div class="progress progress-reflect">
                    <div class="progress-bar" 
                         role="progressbar" 
                         aria-valuenow="{{ $category->decCompletion }}" 
                         aria-valuemin="0" 
                         aria-valuemax="1"
                         style="width: {{ $category->decCompletion / 1 * 100 }}%;
                                background-color: #361163;">
                        {{ $category->completion }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>