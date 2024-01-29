<div class="checkout-text">
    <div class="back-to-home">
        <a href="https://www.fluffici.eu">Back to Fluffici</a>
        <img src="https://img.icons8.com/sf-regular/48/FFFFFF/forward.png" alt="Arrow" height="15px" width="15px" />
        <small class="selected-text">Contact Information</small>
        <img src="https://img.icons8.com/sf-regular/48/FFFFFF/forward.png" alt="Arrow" height="15px" width="15px" />
        <small class="gray-text">Payment</small>
        <img src="https://img.icons8.com/sf-regular/48/FFFFFF/forward.png" alt="Arrow" height="15px" width="15px" />
        <small class="gray-text">Confirmed</small>
    </div>

    <div class="card-details">
        <form action="{{ url('/create-order') }}" method="POST" id="billing-form">
            @csrf
            <label>Personal information
                <input name="first-name" type="text" placeholder="First name" required/>
                <input name="last-name" type="text" placeholder="Last name" required/>
                <input name="email" type="email" placeholder="John.doe@gmail.com" required/>
                <input name="phone" type="tel" placeholder="(+420) 000-000" required/>
            </label>

            <label>Delivery information
                <input name="address-one" type="text" placeholder="Address" required/>
                <input name="address-two" type="text" placeholder="Complementary address" required/>
                <input name="zip-code" type="number" placeholder="Zip code" required/>
                <input name="city" type="text" placeholder="City" required/>
            </label>

            <div class="country-region">
                <label>Country or region
                    <select name="country" form="billing-form" required>
                        @if(empty($countries))
                            <option style="color: black">No country available</option>
                        @else
                            @foreach($countries as $country)
                                <option style="color: black" value="{{ $country->iso_code }}">{{ $country->country_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </label>
            </div>

            <div class="country-region">
                <label>Delivery information
                    <select name="carrier" form="billing-form" required>
                        @if(empty($carriers))
                            <option>No carriers available</option>
                        @else
                            @foreach($carriers as $carrier)
                                <option value="{{ $carrier->slug }}">{{ $carrier->carrierName }} +{{ $carrier->carrierPrice }} Kc</option>
                            @endforeach
                        @endif
                    </select>
                </label>
            </div>

            <label>
                <input type="number" name="productId" value="{{ $productId }}" hidden="">
                <input type="number" name="productPrice" value="{{ $productPrice }}" hidden="">
                <input type="text" name="productName" value="{{ $productName }}" hidden="">
            </label>

            <input class="buy-button" type="submit" value="Go to payment">
        </form>
    </div>

    @include('shop/clearfix')
</div>

