<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Shop Monthly Report</title>
    <link href="https://dashboard.rsiniya.uk/css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <div style="page-break-after:auto;">
    <div class="tm_container">
        <div class="tm_invoice_wrap">
            <div class="tm_invoice tm_style1">
                <div class="tm_invoice_in">
                    <div class="tm_invoice_head tm_mb20">
                        <div class="tm_invoice_left">
                            <div class="tm_logo tm_size1">
                                <img src="" alt="logo">
                            </div>
                        </div>
                        <div class="tm_invoice_right tm_text_right">
                            <b class="tm_f20 tm_medium tm_primary_color">Shop Monthly Report</b>
                            <p class="tm_m0 tm_f12"></p>
                        </div>
                    </div>
                    <hr class="tm_mb8">
                    <div class="tm_flex tm_flex_column_sm tm_justify_between tm_align_center tm_align_start_sm tm_medium tm_mb10">
                        <p class="tm_m0">Report No: <br><b class="tm_primary_color">{{ $reportId }}</b></p>
                        <p class="tm_m0">Invoice Date: <br><b class="tm_primary_color">{{ $reportDate }}</b></p>
                        <p class="tm_m0">Date of Export: <br><b class="tm_primary_color">{{ $reportExportDate }}</b></p>

                        <p class="tm_m0">Loss: <br><b class="tm_primary_color" style="color: indianred;">{{ $lossPercentage }}% of the profit has been lost because of sales.</b></p>
                    </div>
                    <hr class="tm_mb20">
                    <div class="tm_table tm_style1">
                        <div class="tm_border">
                            <div class="tm_table_responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="tm_width_6 tm_semi_bold tm_primary_color tm_gray_bg">Product</th>
                                            <th class="tm_width_2 tm_semi_bold tm_primary_color tm_gray_bg">Price</th>
                                            <th class="tm_width_2 tm_semi_bold tm_primary_color tm_gray_bg">Qty</th>
                                            <th class="tm_width_2 tm_semi_bold tm_primary_color tm_gray_bg tm_text_right">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(empty($reportProducts))
                                            <tr>
                                                <td class="tm_width_6">No orders passed this month.</td>
                                                <td class="tm_width_2"></td>
                                                <td class="tm_width_2"></td>
                                                <td class="tm_width_2 tm_text_right"></td>
                                            </tr>
                                        @else
                                            @foreach($reportProducts as $product)
                                                <tr>
                                                    <td class="tm_width_6">{{$product->product_name}}</td>
                                                    <td class="tm_width_2">{{$product->price}}</td>
                                                    <td class="tm_width_2">{{$product->quantity}}</td>
                                                    <td class="tm_width_2 tm_text_right">{{$product->price * $product->quantity}}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tm_invoice_footer tm_mb30 tm_m0_md">
                            <div class="tm_right_footer">
                                <br>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td class="tm_width_3 tm_primary_color tm_border_none tm_pt0">Fees <span class="tm_ternary_color">(5%)</span></td>
                                            <td class="tm_width_3 tm_success_color tm_text_right tm_border_none tm_pt0" style="color: lime;">+ {{ $fees }} Kc</td>
                                        </tr>
                                        <tr>
                                            <td class="tm_width_3 tm_primary_color tm_border_none tm_pt0">Sales Loss</td>
                                            <td class="tm_width_3 tm_danger_color tm_text_right tm_border_none tm_pt0" style="color: orangered;">- {{ $sales }} (-{{ $lossPercentage  }}%) Kc</td>
                                        </tr>
                                        <tr>
                                            <td class="tm_width_3 tm_primary_color tm_border_none tm_bold">Overall Monthly Profit</td>
                                            <td class="tm_width_3 tm_success_color tm_text_right tm_border_none tm_bold" style="color: lime;">+ {{ $overallProfit }} Kc</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<style>
    .page-break {
        page-break-before: always;
    }

    .invoice-articles-table {
        padding-bottom: 50px; //height of your footer
    }
</style>

</html>
