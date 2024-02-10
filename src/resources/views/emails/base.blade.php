<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta content="telephone=no" name="format-detection" />
    <title></title>
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    <style data-premailer="ignore">
        @media screen and (max-width: 600px) {
            u+.body {
                width: 100vw !important;
            }
        }

        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }
    </style>
    <!--[if mso]>
    <style type="text/css">
        body, table, td {
            font-family: Arial, Helvetica, sans-serif !important;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        .box {
            border-color: #eee !important;
        }
    </style>
    <![endif]-->
    <!--[if !mso]><!-->
    <link href="https://rsms.me/inter/inter.css" rel="stylesheet" type="text/css" data-premailer="ignore">
    <style type="text/css" data-premailer="ignore">
        @import url(https://rsms.me/inter/inter.css);
    </style>
    <!--<![endif]-->
    <link rel="stylesheet" href="{{ url('css/theme.css') }}" />
</head>

<body class="bg-body theme-dark">
<center>
    <table class="main bg-body" width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center" valign="top">
                <!--[if (gte mso 9)|(IE)]>
                <table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center" valign="top" width="640">
                <![endif]-->
                <table class="wrap" cellspacing="0" cellpadding="0">
                    <tr>
                        <td class="p-sm">
                            <table cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="py-lg">
                                        <table cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td>
                                                    <a href="https://fluffici.eu"><img src="https://autumn.fluffici.eu/attachments/jVrNMLSH1BNA5ZnqGhpLGhVkFoteCwM_Lq0Y5G9Ij7" width="116" alt="" /></a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <div class="main-content">
                                @yield('content')
                            </div>
                            <table cellspacing="0" cellpadding="0">
                                <tr>
                                    <td class="py-xl">
                                        <table class="text-center text-muted" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td align="center" class="pb-md">
                                                    <table class="w-auto" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            @foreach($socials as $social)
                                                                <td class="px-sm">
                                                                    <a href="{{ $social->url }}">
                                                                        <img src="{{ url('icons/' . $social->slug) }}" class=" va-middle" width="24" height="24" alt="brand-{{ $social->slug }}" />
                                                                    </a>
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="px-lg">
                                                    If you have any questions, feel free to message us at <a href="https://shop.fluffici.eu/support" class="text-muted">shop.fluffici.eu/support</a>.
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <!--[if (gte mso 9)|(IE)]>
                </td>
                </tr>
                </table>
                <![endif]-->
            </td>
        </tr>
    </table>
</center>
</body>
</html>
