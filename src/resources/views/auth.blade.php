@extends('app')


@section('body')

    <div class="container-md ">
        <div class="form-signin h-full min-vh-100 d-flex flex-column justify-content-center ">

            <a class="d-flex justify-content-center mb-4 p-0 px-sm-5" href="">
                <img src="https://autumn.fluffici.eu/attachments/jVrNMLSH1BNA5ZnqGhpLGhVkFoteCwM_Lq0Y5G9Ij7" alt="fluffici_banner"  class="banner" style="width: auto; bottom: 5px;align-items: flex-end;">
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
