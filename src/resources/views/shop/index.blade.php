<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="csrf_token" content="{{  csrf_token() }}" id="csrf_token">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <script
            src="https://kit.fontawesome.com/7433d3320f.js"
            crossorigin="anonymous"
        ></script>
        <script src="{{ mix('/js/manifest.js','vendor/fluffici') }}" type="text/javascript"></script>
        <script src="{{ mix('/js/vendor.js','vendor/fluffici') }}" type="text/javascript"></script>
        <script src="{{ mix('/js/dashy.js','vendor/fluffici') }}" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="{{  mix('/css/dashy.css','vendor/fluffici') }}">

        <title>Fluffici Store</title>
    </head>
    <body>
        <div class="row justify-content-center d-md-flex h-100">

            <div class="col-xxl col-xl-9 col-12 dark:bg-dots-lighter bg-fluffici-dark" id="custom">

            </div>
        </div>

        @include('partials.toast')
    </body>
</html>
