@push('head')
    <meta name="robots" content="noindex"/>
    <meta name="google" content="notranslate">
    <link
          href="https://autumn.rsiniya.uk/attachments/l4bhc6CfxTm87LqQdOqy3llou-841DVP1GK6qtyO6l/favicon.png?width=128&height=128"
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
        <x-orchid-icon path="bs.house" class="icon-menu"/>
    @endauth


    <p class="my-0 {{ auth()->check() ? 'd-none d-xl-block' : '' }}">
        {{ config('app.name') }}
    </p>
</div>
