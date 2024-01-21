<div class="ui visible sidebar right floated dark:bg-gray-900 flex-lg-wrap float-xl-end" id="members">
    <header class="d-xl-block p-3 mt-xl-4 w-100 d-flex align-items-center">
        <div class="h2 d-flex align-items-center">
            <p class="my-0 " style="font-family: Montserrat; font-size: large; color: white">
                Members
            </p>
        </div>
    </header>


    <nav class="aside-collapse w-100 d-xl-flex flex-column collapse-horizontal">
        @include('partials.search')

        <div class="custom-loader" style="left: 40%; position: fixed;  " id="loading-members"></div>
        <small id="member-loading" class="centered-bar styled" style="font-size: small; color: white;"> Loading members... </small>


        <div id="navbar-content">
            <ul class="nav flex-column mb-md-1 mb-auto ps-0" id="membersList"></ul>
        </div>

        <div class="h-100 w-100 position-relative to-top cursor d-none d-md-flex mt-md-5"
             data-action="click->html-load#goToTop"
             title="{{ __('Scroll to top') }}">
            <div class="bottom-left w-100 mb-2 ps-3 overflow-hidden">
                <small data-controller="viewport-entrance-toggle"
                       class="scroll-to-top"
                       data-viewport-entrance-toggle-class="show">
                    <x-orchid-icon path="bs.chevron-up" class="me-2"/>
                    {{ __('Scroll to top') }}
                </small>
            </div>
        </div>
    </nav>
</div>
