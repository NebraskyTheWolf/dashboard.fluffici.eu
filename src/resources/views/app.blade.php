<!DOCTYPE html>
<html lang="{{  app()->getLocale() }}" data-controller="html-load" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">
    <meta name="author" content="Fluffici">
    <meta name="description" content="Fluffici Admin Panel">

    <title>
        @yield('title', 'Fluffici')
        @hasSection('title')
            - Fluffici
        @endif
    </title>
    <meta name="csrf_token" content="{{  csrf_token() }}" id="csrf_token">
    <meta name="auth" content="{{  Auth::check() }}" id="auth">
    <link rel="stylesheet" type="text/css" href="{{  mix('/css/dashy.css','vendor/fluffici') }}">

    @stack('head')

    <meta name="turbo-root" content="{{  Dashboard::prefix() }}">
    <meta name="dashboard-prefix" content="{{  Dashboard::prefix() }}">
    <meta name="turbo-cache-control" content="no-cache">

    <meta property="og:image" content="https://autumn.fluffici.eu/attachments/jVrNMLSH1BNA5ZnqGhpLGhVkFoteCwM_Lq0Y5G9Ij7" />
    <meta property="og:image:secure_url" content="https://autumn.fluffici.eu/attachments/jVrNMLSH1BNA5ZnqGhpLGhVkFoteCwM_Lq0Y5G9Ij7" />
    <meta property="og:image:type" content="image/png" />
    <meta property="og:image:width" content="300" />

    <meta name="og:title" content="@yield('title') • Fluffici"/>
    <meta name="og:type" content="website"/>

    <meta name="copyright" content="Fluffici">
    <meta name="webmaster" content="Vakea, vakea@fluffici.eu">

    <meta name="contact" content="administrace@fluffici.eu">

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta content="yes" name="apple-touch-fullscreen" />
    <meta name="apple-mobile-web-app-status-bar-style" content="red">
    <meta name="format-detection" content="telephone=no">
    <meta name="theme-color" content="#FF002E">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <script src="{{ mix('/js/manifest.js','vendor/fluffici') }}" type="text/javascript"></script>
    <script src="{{ mix('/js/vendor.js','vendor/fluffici') }}" type="text/javascript"></script>
    <script src="{{ mix('/js/dashy.js','vendor/fluffici') }}" type="text/javascript"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script type="text/javascript" src="{{ url('/js/app.js') }}"></script>
    <script src="https://js.pusher.com/8.0.1/pusher.min.js"></script>
    <script src="https://js.pusher.com/beams/1.0/push-notifications-cdn.js"></script>

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

<div data-controller="@yield('controller')" @yield('controller-data') id="loading">
    <div class="row justify-content-center d-md-flex h-100">
        @yield('aside')

        <div class="col-xxl col-xl-9 col-12 dark:bg-dots-lighter bg-fluffici-dark">
            @yield('body')
        </div>
    </div>

    @include('partials.toast')
</div>

@stack('scripts')
@yield('script')

@if (\Illuminate\Support\Facades\Auth::check())
    <input id="userId" type="number" value="{{ \Illuminate\Support\Facades\Auth::id() }}" hidden="">
@else
    <input id="userId" type="number" value="0" hidden="">
@endif

<script>
    const beamsClient = new PusherPushNotifications.Client({
        instanceId: "63e32cff-b20c-4c92-bb49-0e40cfd1dbe3",
    });

    beamsClient
        .start()
        .then((beamsClient) => beamsClient.getDeviceId())
        .then((deviceId) =>
            console.log("Successfully registered with Beams. Device ID:", deviceId)
        )
        .then(() => beamsClient.addDeviceInterest("dashboard"))
        .then(() => beamsClient.getDeviceInterests())
        .then((interests) => console.log("Current interests:", interests))
        .catch(console.error);
</script>

</body>
</html>
