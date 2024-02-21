
<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Laralink">
    <!-- Site Title -->
    <title></title>
    <link rel="stylesheet" href="https://dashboard.rsiniya.uk/css/style.css">
</head>

<body>
<div class="tm_container">
    <div class="tm_invoice_wrap">
        <div class="tm_invoice tm_style1" id="tm_download_section">
            <div class="tm_invoice_in">
                <div class="tm_invoice_head tm_align_center tm_mb20">
                    <div class="tm_invoice_left">
                        <div class="tm_logo"><img src="https://autumn.fluffici.eu/attachments/jVrNMLSH1BNA5ZnqGhpLGhVkFoteCwM_Lq0Y5G9Ij7" alt="Logo"></div>
                    </div>
                    <div class="tm_invoice_right tm_text_right">
                        <div class="tm_primary_color tm_f50 tm_text_uppercase">Invoice</div>
                    </div>
                </div>
                <div class="tm_invoice_info tm_mb20">
                    <div class="tm_invoice_seperator tm_gray_bg"></div>
                    <div class="tm_invoice_info_list">
                        <p class="tm_invoice_number tm_m0">Invoice No: <b class="tm_primary_color">#{{ $invoiceId }}</b></p>
                        <p class="tm_invoice_number tm_m0">Order No: <b class="tm_primary_color">#{{ $orderId }}</b></p>
                        <p class="tm_invoice_date tm_m0">Date: <b class="tm_primary_color">{{ $issuedAt }}</b></p>
                    </div>
                </div>
                <div class="tm_invoice_head tm_mb10">
                    <div class="tm_invoice_left">
                        <p class="tm_mb2"><b class="tm_primary_color">Invoice To:</b></p>
                        <p>
                            {{ $first_name }} . {{ $last_name }} <br>
                            {{ $address_one }}
                            <br>
                            {{ $address_two }} <br>{{ $country }}<br>
                            {{ $email }}
                        </p>
                    </div>
                    <div class="tm_invoice_right tm_text_right">
                        <p class="tm_mb2"><b class="tm_primary_color">Pay To:</b></p>
                        <p>
                            Fluffici z.s <br>
                            Czechia<br>
                            administrace@fluffici.eu
                        </p>
                    </div>
                </div>
                <div class="tm_table tm_style1">
                    <div class="tm_round_border tm_radius_0">
                        <div class="tm_table_responsive">
                            <table>
                                <thead>
                                <tr>
                                    <th class="tm_width_3 tm_semi_bold tm_primary_color tm_gray_bg">Item</th>
                                    <th class="tm_width_2 tm_semi_bold tm_primary_color tm_gray_bg">Price</th>
                                    <th class="tm_width_1 tm_semi_bold tm_primary_color tm_gray_bg">Qty</th>
                                    <th class="tm_width_2 tm_semi_bold tm_primary_color tm_gray_bg tm_text_right">Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                        <tr class="tm_table_baseline">
                                            <td class="tm_width_3 tm_primary_color">{{ $product->name }}</td>
                                            <td class="tm_width_2">{{ $product->price }} Kc</td>
                                            <td class="tm_width_1">{{ $product->quantity }}</td>
                                            <td class="tm_width_2 tm_text_right">{{ $product->price * $product->quantity }} Kc</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tm_invoice_footer tm_border_left tm_border_left_none_md">
                        <div class="tm_left_footer tm_padd_left_15_md">
                            <p class="tm_mb2"><b class="tm_primary_color">Payment info:</b></p>
                            <p class="tm_m0">{{ $paymentMethod }} <br>Amount: {{ $paymentPrice }} Kc</p>
                        </div>
                        <div class="tm_right_footer">
                            <table>
                                <tbody>
                                <tr class="tm_gray_bg tm_border_top tm_border_left tm_border_right">
                                    <td class="tm_width_3 tm_primary_color tm_border_none tm_bold">Subtoal</td>
                                    <td class="tm_width_3 tm_primary_color tm_text_right tm_border_none tm_bold">{{ $subTotal }} Kc</td>
                                </tr>
                                <tr class="tm_gray_bg tm_border_left tm_border_right">
                                    <td class="tm_width_3 tm_primary_color tm_border_none tm_pt0">Discount <span class="tm_ternary_color">({{ $discountPer }}%)</span></td>
                                    <td class="tm_width_3 tm_text_right tm_border_none tm_pt0 tm_danger_color">- {{ $discount }} Kc</td>
                                </tr>
                                <tr class="tm_gray_bg tm_border_left tm_border_right">
                                    <td class="tm_width_3 tm_primary_color tm_border_none tm_pt0">Tax <span class="tm_ternary_color">({{ $taxPer }}%)</span></td>
                                    <td class="tm_width_3 tm_primary_color tm_text_right tm_border_none tm_pt0">+ {{ $tax }} Kc</td>
                                </tr>
                                <tr class="tm_gray_bg tm_border_left tm_border_right">
                                    <td class="tm_width_3 tm_primary_color tm_border_none tm_pt0">Delivery fee</td>
                                    <td class="tm_width_3 tm_primary_color tm_text_right tm_border_none tm_pt0">+ {{ $carrierPrice }} Kc</td>
                                </tr>
                                <tr class="tm_border_top tm_gray_bg tm_border_left tm_border_right">
                                    <td class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_primary_color">Grand Total	</td>
                                    <td class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_primary_color tm_text_right">{{ $grandTotal }} Kc</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <hr class="tm_mb20">
                <div class="tm_text_center">
                    <p class="tm_mb5"><b class="tm_primary_color">Return Policy:</b></p>
                    <p class="tm_m0"> {!! $returnPolicy !!} </p>
                    <p class="tm_mb5"><b class="tm_primary_color">Invoice note:</b></p>
                    <p class="tm_m0"> {!! $note !!} </p>
                </div><!-- .tm_note -->
            </div>
        </div>
    </div>
</div>
</body>
</html>
