<!DOCTYPE html>
<html lang="cz">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Transactions Monthly Report</title>
        <link href="https://dashboard.fluffici.eu/css/style.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div style="page-break-after:auto;">
            <div class="">
                <div class="">
                    <div class="tm_invoice tm_style1 ">
                        <div class="">
                            <div class="tm_invoice_head tm_align_center tm_mb20">
                                <div class="tm_invoice_left">
                                    <div class="tm_logo">
                                        <img src="" alt="Logo">
                                    </div>
                                </div>
                                <div class="tm_invoice_right tm_text_right">
                                    <div class="tm_primary_color tm_f20 tm_text_uppercase">Transactions</div>
                                </div>
                            </div>
                            <div class="tm_invoice_info tm_mb20">
                                <div class="tm_invoice_seperator tm_gray_bg"></div>
                                <div class="tm_invoice_info_list">
                                    <p class="tm_invoice_number tm_m0">Report No: <b class="tm_primary_color">{{ $reportId }}</b></p>
                                    <p class="tm_invoice_date tm_m0">Date: <b class="tm_primary_color">{{ $reportDate }}</b></p>
                                </div>
                            </div>
                            <div class="tm_invoice_head tm_mb10">
                                <div class="tm_invoice_left">
                                    <p class="tm_mb2"><b class="tm_primary_color">Report To:</b></p>
                                    <p>
                                        Fluffici <br>
                                        <br>Czechia <br>
                                        contact@fluffici.eu
                                    </p>
                                </div>
                            </div>
                            <div class="tm_table tm_style1 tm_mb30">
                                <div class="tm_round_border">
                                    <div class="tm_table_responsive">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th class="tm_width_3 tm_semi_bold tm_primary_color tm_gray_bg">Order ID</th>
                                                    <th class="tm_width_4 tm_semi_bold tm_primary_color tm_gray_bg">Transaction Id</th>
                                                    <th class="tm_width_2 tm_semi_bold tm_primary_color tm_gray_bg">Provider</th>
                                                    <th class="tm_width_1 tm_semi_bold tm_primary_color tm_gray_bg">Status</th>
                                                    <th class="tm_width_2 tm_semi_bold tm_primary_color tm_gray_bg tm_text_right">Total</th>
                                                    <th class="tm_width_2 tm_semi_bold tm_primary_color tm_gray_bg tm_text_right">Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($transactions as $transaction)
                                                    <tr>
                                                        <td class="tm_width_3">{{ $transaction->getOrderId() }}</td>
                                                        <td class="tm_width_4">{{ substr($transaction->transaction_id, 0, 8) }}</td>
                                                        <td class="tm_width_2">{{ $transaction->provider }}</td>
                                                        <td class="tm_width_1">{{ $transaction->status }}</td>
                                                        <td class="tm_width_2 tm_text_right">{{ $transaction->price }} Kc</td>
                                                        <td class="tm_width_2 tm_text_right">{{ $transaction->created_at }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tm_invoice_footer">
                                    <div class="tm_left_footer">
                                        <p class="tm_mb2"><b class="tm_primary_color">Overdue info:</b></p>
                                        <p class="tm_m0">Overdue: {{ $overdueAmount }} Kc</p>
                                    </div>
                                    <div class="tm_right_footer">
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td class="tm_width_3 tm_primary_color tm_border_none tm_bold">Subtoal</td>
                                                    <td class="tm_width_3 tm_primary_color tm_text_right tm_border_none tm_bold">{{ $overallProfit }} Kc</td>
                                                </tr>
                                                <tr>
                                                    <td class="tm_width_3 tm_primary_color tm_border_none tm_pt0">Carrier Fees</td>
                                                    <td class="tm_width_3 tm_primary_color tm_text_right tm_border_none tm_pt0">+{{ $fees }} Kc</td>
                                                </tr>
                                                <tr class="tm_border_top tm_border_bottom">
                                                    <td class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_primary_color">Grand Total	</td>
                                                    <td class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_primary_color tm_text_right">{{ $overallProfit }} Kc</td>
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
        </div>
    </body>
</html>
