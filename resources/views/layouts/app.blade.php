<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'sciReflect') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Global app data -->
    <script>
        window.sciReflect = {};
        @yield('sciReflect')
    </script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top @yield('navbar-header-class')">
            <div class="container-fluid">
                <div class="navbar-header">
                    @isset($activity)
                        <a class="navbar-brand" href="{{ url($homeUrl) }}">
                    @else
                        <p class="navbar-brand">
                    @endisset
                        {{ config('app.name', 'sciReflect') }}
                    @isset($activity)
                        </a>
                    @else
                        </p>
                    @endisset
                </div>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <h4 class="navbar-text">@yield('title')</h4>

                    @if (Auth::check() && Auth::user()->role == 'staff')
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    Edit Activity
                                </li>
                                <li>
                                    Rate students
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>

            </div>
        </nav>
        <div class="container-fluid">
            @if(session('message'))
                <div class="alert alert-info">
                    {{ session('message') }}
                </div>
            @endif
            @isset($message)
                <div class="alert alert-info">
                    {{ $message }}
                </div>
            @endisset
            @yield('content')
        </div>
    </div>

    {{-- included JS varies depending on user role --}}
    @if (Auth::check() && Auth::user()->role == 'staff')
        <script src="{{ asset('js/app-admin.js') }}"></script>
    @else
        <script src="{{ asset('js/app.js') }}"></script>
    @endif
</body>
</html>
