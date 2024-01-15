<html lang="{{ config('app.locale') }}">
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title') - Fluffici</title>

        <link rel="stylesheet" href="{{ url('/css/semantic.min.css') }}">
        <link rel="stylesheet" href="{{ url('/css/custom-responsive.css') }}">
        <link rel="stylesheet" href="{{ url('/css/app.css') }}">
        @yield('style')

        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
            crossorigin="anonymous">
        </script> 

        <link rel="icon" type="image/png" href="{{ url('/img/favicon.png') }}" />
        </head>
        <body class="front">
        <div class="pusher">
            @include('layouts.header')

            <div class="container">
                @yield('content')
            </div>

            @include('layouts.footer')
        </div>
            <script type="text/javascript" src="{{ url('/js/semantic.min.js') }}"></script>
            <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
            <script type="text/javascript">
                @if (session('flash.success'))
                    toastr.success("")
                @endif
                @if (session('flash.error'))
                    toastr.error("")
                @endif
                @if (session('flash.warning'))
                    toastr.warning("")
                @endif
                @if (session('flash.info'))
                    toastr.info("")
                @endif
            </script>
            <script type="text/javascript" src="{{ url('/js/app.js') }}"></script>
            <script type="text/javascript" src="{{ url('/js/form.js') }}"></script>
            @yield('script')
        </body>
</html>