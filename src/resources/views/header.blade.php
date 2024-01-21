@push('head')
    <meta name="robots" content="noindex"/>
    <meta name="google" content="notranslate">
    <link
          href="https://autumn.rsiniya.uk/attachments/xoc4VuUvf2F0D7v1qLHhXhhXFAMzk-yFF47-oJypue/favicon.png?width=128&height=128"
          sizes="any"
          type="image/png"
          id="favicon"
          rel="icon"
    >

    <!-- For Safari on iOS -->
    <meta name="theme-color" content="#21252a">
@endpush

<div class="h2 d-flex align-items-center">
    @auth
        <i class="home icon"></i>
    @endauth


    <p class="my-0 {{ auth()->check() ? 'd-none d-xl-block' : '' }}">
        {{ config('app.name') }}
    </p>
</div>
