@extends('app')


@section('body')

    <div class="container-md dark:bg-dots-lighter bg-dark">
        <div class="form-signin h-full min-vh-100 d-flex flex-column justify-content-center ">

            <a class="d-flex justify-content-center mb-4 p-0 px-sm-5" href="{{Dashboard::prefix()}}">
                <img src="https://autumn.rsiniya.uk/attachments/HCnIXi2Qg4QkxylKY_5cjJ9J5LuhRPwv6C-ePizB3F?width=300" alt="fluffici_banner" class="banner">
            </a>

            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-5 col-xxl-5 px-md-5">

                    <div class="p-4 p-sm-5 rounded shadow-sm">
                        @yield('content')
                    </div>
                </div>
            </div>

            @includeFirst(['footer'])
        </div>
    </div>

@endsection
