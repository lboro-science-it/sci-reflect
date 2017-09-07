@extends('layouts.student')

@section('title')

{{ $round->title }}: Review your skills

@endsection

@section('content')

<div class="row">

    {{-- Chart sidebar --}}
    <div class="col-md-3 col-md-push-7 col-sm-4 col-sm-push-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>{{ $round->title }}
                    @if($rater->id != Auth::user()->id)
                        <small>(rated by {{ $rater->name }})</small>
                    @endif
                </h3>
            </div>
            <div class="panel-body" style="padding: 20px;">
                @include('partials.chart.chart', ['chartData' => $chartData])
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-body text-center">
                 <a href="{{ url($homeUrl) }}" class="btn btn-primary btn-lg" role="button">Return to Dashboard</a>
             </div>
         </div>

        @include('partials.rounds.completed', ['rounds' => $rounds->completed->whereNotIn('id', $round->id)])

    </div>

    <div class="col-md-5 col-md-pull-3 col-md-offset-2 col-sm-8 col-sm-pull-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>
                    Your skills
                    @if($rater->id != Auth::user()->id)
                        <small>(rated by {{ $rater->name }})</small>
                    @endif
                </h3>
            </div>
            <div class="panel-body">

                @if($categories->count() > 0)
                    @foreach($categories as $category)
                        @if($skills->where('category_id', $category->id)->count() > 0)
                            <h3>{{ $category->name }}</h3>
                            @foreach($skills->where('category_id', $category->id) as $skill)
                                <div class="row">
                                    @include('partials.ratings.horizontal', ['skill' => $skill, 'category' => $category, 'improve' => true])
                                </div>
                            @endforeach
                        @endif
                    @endforeach
                @else
                    @foreach($skills as $skill)
                        <div class="row">
                            @include ('partials.ratings.horizontal', ['skill' => $skill, 'category' => $skill->category, 'improve' => true])
                        </div>
                    @endforeach
                @endif

            </div>
        </div>

    </div>

</div>

@endsection