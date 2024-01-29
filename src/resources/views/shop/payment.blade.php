<div class="checkout-text" id="payment">
    <div class="back-to-home">
        <a href="https://www.fluffici.eu">Back to Fluffici</a>
        <img src="https://img.icons8.com/sf-regular/48/aaaaaa/forward.png" alt="Arrow" height="15px" width="15px" />
        <small class="gray-text">Contact Information</small>
        <img src="https://img.icons8.com/sf-regular/48/aaaaaa/forward.png" alt="Arrow" height="15px" width="15px" />
        <small class="selected-text">Payment</small>
        <img src="https://img.icons8.com/sf-regular/48/aaaaaa/forward.png" alt="Arrow" height="15px" width="15px" />
        <small class="gray-text">Confirmed</small>
    </div>

    <div class="pay-button">
        <form action="{{ url('/payment') }}" method="post">
            @csrf

            <input type="text" name="order-id" value="{{ $orderId }}" hidden>
            <input type="text" name="payment-type" value="outing" hidden>

            @if ($productPrice <= 0)
                <input type="submit" value="Get at outing" class="outing-button">
            @else
                <input type="submit" value="Payment at outing" class="outing-button">
            @endif
        </form>
    </div>

    <div class="or-line">
        @if($productPrice <= 0)
            <p>Or pay just the delivery fee.</p>
        @else
            <p>Or pay with card</p>
        @endif
    </div>

    <div class="card-details">
        <form action="{{ url('/payment') }}" method="post">
            @csrf
            <input type="text" name="order-id" value="{{ $orderId }}" hidden>

            @if ($productPrice <= 0)
                <input type="text" name="payment-type" value="free" hidden>

                <input type="submit" value="Get for free" class="buy-button">
            @else
                <input type="text" name="payment-type" value="bank-card" hidden>

                <label>Card details
                    <div class="card-number">
                        <input name="card-number" type="number" placeholder="4242 4242 4242 4242" />
                        <div>
                            <img src="https://img.icons8.com/color/48/000000/visa.png" height="20px" width="20px"  alt=""/>
                            <img src="https://img.icons8.com/color/48/000000/mastercard.png" height="20px" width="20px"  alt=""/>
                        </div>
                    </div>
                    <div class="card-info">
                        <div class="mm-yy">
                            <input name="card-expiration" type="number" placeholder="MM / YY" />
                        </div>
                        <div class="cvc">
                            <input name="card-cvc" type="number" placeholder="CVC" />
                            <img src="https://img.icons8.com/ios/50/000000/card-verification-value.png" height="20px" width="20px"  alt=""/>
                        </div>
                    </div>
                </label>
                <input type="submit" value="Pay {{ $productPrice }} Kc" class="buy-button">
            @endif
        </form>
    </div>

    @if ($productPrice <= 0)
        @for($i = 0; $i < 16; $i++)
            @include('shop/clearfix')
        @endfor
    @endif

    @include('shop/clearfix')
</div>
