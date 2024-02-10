@push('head')
    <meta name="robots" content="noindex"/>
    <meta name="google" content="notranslate">
    <link
          href="https://autumn.fluffici.eu/attachments/xWan5czeFVVxHsW8_iY5x6qNWu2m7rs4EfNTrPn2HC/favicon.png?width=128&height=128"
          sizes="any"
          type="image/png"
          id="favicon"
          rel="icon"
    >

    <meta property="og:image" content="https://autumn.fluffici.eu/attachments/eI0QemKZhF6W9EYnDl5JcBGYGvPiIxjPzvrDY_9Klk" />
    <meta property="og:image:secure_url" content="https://autumn.fluffici.eu/attachments/eI0QemKZhF6W9EYnDl5JcBGYGvPiIxjPzvrDY_9Klk" />
    <meta property="og:image:type" content="image/png" />
    <meta property="og:image:width" content="128" />

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
@endpush

<div class="h2 d-flex align-items-center">
    <p class="my-0 {{ auth()->check() ? 'd-none d-xl-block' : '' }}">
        <img src="https://autumn.fluffici.eu/attachments/jVrNMLSH1BNA5ZnqGhpLGhVkFoteCwM_Lq0Y5G9Ij7?width=200" alt="fluffici_logo">
    </p>
</div>
