<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $productName  }} - Checkout</title>
    <link rel="stylesheet" type="text/css" href="{{ url('/css/shop.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ url('/css/loader.css') }}"/>
    <meta name="csrf_token" content="{{  csrf_token() }}" id="csrf_token">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="{{ url('/js/success.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

</head>
<body style="background-color:#0E1414;">

<img src="https://autumn.rsiniya.uk/attachments/p-px4u5NHSs2jydtnxzEq4NFzRDt_em5kILZj3tna9" alt="fluffici_banner"  class="banner" style="width: auto; bottom: 5px;align-items: flex-end;">

<div class="container">
    <div class="wrapper">
        <div class="checkout-image">
            <div class="info">
                <h4>{{ $productName  }}</h4>

                @if ($productPrice <= 0)
                    <h1>Free</h1>
                @else
                    @if($originalPrice != $productPrice)
                        <h1>{{ $productPrice }} Kc</h1>
                        @if(!$discounted <= 0)
                            <h3 style="color: #FED700;"> {{ $discounted }}% Off </h3>
                        @endif
                    @else
                        <h1>{{ $productPrice }} Kc</h1>
                    @endif
                @endif

                <p>
                    @if(empty($productDescription))
                        <span>No descriptions</span>
                    @else
                        <span>{{ $productDescription }}</span>
                    @endif

                    <img src="https://img.icons8.com/sf-regular/48/aaaaaa/forward.png" alt="Arrow" height="15px"
                         width="15px"/>
                </p>
                <img class="product-img" src="{{ $productURL }}" height="200px" alt="Product-Image"/>
            </div>
            <div class="footer">
                <p>
                    <span>Powered by <b>Fluffici</b></span>
                    <img
                        src="https://img.icons8.com/external-dreamstale-lineal-dreamstale/32/000000/external-warning-ui-dreamstale-lineal-dreamstale.png"
                        alt="Warning-icon" height="15px" width="15px"/>
                </p>
                <label>
                    <select>
                        <option selected>English</option>
                        <option value="cs">Czech</option>
                        <option value="sk">Slovak</option>
                        <option value="de">German</option>
                    </select>
                </label>
            </div>
        </div>

        @if($success)
            @include('shop.success')
        @else
            @if($failed)
                @include('shop.failed')
            @else
                @if($payment)
                    @include('shop.payment')
                @else
                    @include('shop.billing')
                @endif
            @endif
        @endif

    </div>
    <h5>&copy; <b>Fluffici</b> - 2022 Website Made with ❤️ by <a href="https://nebraskythewolf.work" target="_blank">Vakea</a>
    </h5>
</div>
</body>
</html>
