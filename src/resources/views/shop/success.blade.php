<div class="checkout-text">
    <div class="back-to-home">
        <a href="https://www.fluffici.eu">Back to Fluffici</a>
        <img src="https://img.icons8.com/sf-regular/48/aaaaaa/forward.png" alt="Arrow" height="15px" width="15px" />
        <small class="gray-text">Contact Information</small>
        <img src="https://img.icons8.com/sf-regular/48/aaaaaa/forward.png" alt="Arrow" height="15px" width="15px" />
        <small class="gray-text">Payment</small>
        <img src="https://img.icons8.com/sf-regular/48/aaaaaa/forward.png" alt="Arrow" height="15px" width="15px" />
        <small class="selected-text">Confirmed</small>
    </div>

    <div class="card-details">
        <svg id="mainSVG" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 600">
            <defs>
                <g id="confetti">
                    <rect class="paper" width="13" height="8" />
                </g>
            </defs>
            <g id="container"/>
        </svg>

        <h2 class="white-text">Thank your for your purchase {{ $order->first_name }}</h2>
        <small class="white-text">You will receive a email soon with the confirmation on</small>
        <small class="white-text">{{ $order->email }}</small>

    </div>

    @for($i = 0; $i < 50; $i++)
        @include('shop/clearfix')
    @endfor

</div>

<style>
    svg {
        width: 100%;
        height: 100%;
        visibility: hidden;
    }
</style>

<script>
    startAnimation()
</script>
