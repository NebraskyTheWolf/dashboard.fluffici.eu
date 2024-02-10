<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
        <title>404 Not Found</title>
    </head>
    <body style="user-select: none; -moz-user-select: none; -ms-user-select: none; -webkit-user-select: none;">
        <div class="empty">
            <div class="empty-header">403</div>
            <p class="empty-title">Oops… You just found an error page</p>
            <p class="empty-subtitle text-secondary">
                You don't have the permission to access this resource.
            </p>
            <div class="empty-action">
                <a href="{{ route('primary.index') }}" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M5 12l14 0" />
                        <path d="M5 12l6 6" />
                        <path d="M5 12l6 -6" />
                    </svg>
                    Take me home
                </a>
            </div>
        </div>
    </body>
</html>
