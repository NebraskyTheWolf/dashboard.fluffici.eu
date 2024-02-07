<!DOCTYPE html>
<html lang="{{  app()->getLocale() }}" data-controller="html-load" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">
    <meta name="author" content="Fluffici">
    <meta name="description" content="Fluffici Admin Panel">

    <title>
        @yield('title', config('app.name'))
        @hasSection('title')
            - {{ config('app.name') }}
        @endif
    </title>
    <meta name="csrf_token" content="{{  csrf_token() }}" id="csrf_token">
    <meta name="auth" content="{{  Auth::check() }}" id="auth">
    <link rel="stylesheet" type="text/css" href="{{  mix('/css/dashy.css','vendor/fluffici') }}">

    @stack('head')

    <meta name="turbo-root" content="{{  Dashboard::prefix() }}">
    <meta name="dashboard-prefix" content="{{  Dashboard::prefix() }}">
    <meta name="turbo-cache-control" content="no-cache">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <script src="{{ mix('/js/manifest.js','vendor/fluffici') }}" type="text/javascript"></script>
    <script src="{{ mix('/js/vendor.js','vendor/fluffici') }}" type="text/javascript"></script>
    <script src="{{ mix('/js/dashy.js','vendor/fluffici') }}" type="text/javascript"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script type="text/javascript" src="{{url('/js/app.js')}}"></script>

    <link rel="stylesheet" href="{{ url('/css/app.css')}}">
    <link rel="stylesheet" href="{{ url('/css/fluffici.css')}}">
    <link rel="stylesheet" href="{{ url('/css/style.min.css')}}">

    <link rel="stylesheet" href="{{ url('/css/loader.css')}}">

    <link rel="stylesheet" href="{{ url('/semantic/semantic.min.css')}}">
    <script type="text/javascript" src="{{url('/semantic/semantic.min.js')}}"></script>

    @foreach(Dashboard::getResource('stylesheets') as $stylesheet)
        <link rel="stylesheet" href="{{  $stylesheet }}">
    @endforeach

    @stack('stylesheets')

    @foreach(Dashboard::getResource('scripts') as $scripts)
        <script src="{{  $scripts }}" defer type="text/javascript"></script>
    @endforeach
</head>

<body class="{{ \Orchid\Support\Names::getPageNameClass() }} styled-text" data-controller="pull-to-refresh">


<body class="antialiased">
    <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter  selection:bg-red-500 selection:text-white" style="background-color: #2f2b3a;" id="loader" hidden>
        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <div class="mt-16">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                    <img class="centered-bar error-mask" id="error-mark" src="{{url('/img/info.png')}}" alt="error">
                    <div class="centered-bar" id="loader-bar"></div>
                </div>
                <p class="styled centered-bar" id="loading-text"></p>
            </div>
        </div>
    </div>
</body>

<div data-controller="@yield('controller')" @yield('controller-data') id="loading" class="">
    <div class="row justify-content-center d-md-flex h-100">
        @yield('aside')

        <div class="col-xxl col-xl-9 col-12 dark:bg-dots-lighter bg-fluffici-dark" id="custom">
            @yield('body')
        </div>
    </div>

    @include('partials.toast')
</div>

@stack('scripts')
@yield('script')

</body>
</html>
