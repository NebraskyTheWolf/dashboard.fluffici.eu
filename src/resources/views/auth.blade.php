@extends('app')


@section('body')

    <img class="logo" src="https://autumn.rsiniya.uk/attachments/xoc4VuUvf2F0D7v1qLHhXhhXFAMzk-yFF47-oJypue/favicon.png?width=64&height=64" alt="fliffici_logo">


    <div class="container-md">
        <div class="form-signin h-full min-vh-100 d-flex flex-column justify-content-center">

            <a class="d-flex justify-content-center mb-4 p-0 px-sm-5" href="{{Dashboard::prefix()}}">
                @includeFirst(['header'])
            </a>

            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-5 col-xxl-5 px-md-5">

                    <div class="bg-white p-4 p-sm-5 rounded shadow-sm">
                        @yield('content')
                    </div>
                </div>
            </div>

            @includeFirst(['footer'])
        </div>
    </div>

@endsection
