<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Package Invoice</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 14px;
            color: #333;
        }

        /* --- Layout & Utilities --- */
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .width-100 { width: 100%; }
        .width-50 { width: 50%; }
        .width-60 { width: 60%; }
        .width-40 { width: 40%; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        
        /* --- Colors --- */
        /* Updated bg-yellow to actual yellow for the Total bar */
        .bg-yellow { background-color: #cee145; color: #333; } 
        .bg-dark { background-color: #2d3436; color: #fff; }
        .text-muted { color: #777; font-size: 12px; }

        /* --- Header Section --- */
        .header-top {
            padding: 30px 40px 10px 40px;
        }
        .logo {
            max-width: 140px;
            height: auto;
        }
        
        /* --- Invoice Banner --- */
        .invoice-banner {
            background-color: #fff;
            padding: 10px 40px;
            margin-bottom: 30px;
            border-bottom: 3px solid #cee145;
        }
        .invoice-banner h1 {
            margin: 0;
            font-size: 32px;
            text-align: right;
            text-transform: uppercase;
            font-weight: 300;
            letter-spacing: 2px;
        }

        /* --- Client & Details --- */
        .info-table {
            width: 100%;
            padding: 0 40px;
            margin-bottom: 30px;
            border-collapse: separate;
            border-spacing: 0;
        }
        .info-title {
            font-size: 12px;
            color: #777;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
            display: block;
            font-weight: bold;
        }
        .info-data {
            font-size: 14px;
            margin-bottom: 15px;
            line-height: 1.4;
        }

        /* --- Main Items Table --- */
        .items-container {
            padding: 0 40px;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table.items th {
            background-color: #2d3436;
            color: #fff;
            padding: 12px 15px;
            text-align: left;
            font-weight: normal;
            font-size: 13px;
            text-transform: uppercase;
        }
        table.items td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            color: #555;
        }
        table.items tr:nth-child(even) td {
            background-color: #fcfcfc;
        }
        .item-type {
            font-size: 11px;
            background: #eee;
            padding: 2px 6px;
            border-radius: 4px;
            color: #666;
            text-transform: uppercase;
        }

        /* --- Totals & Footer --- */
        .totals-section {
            padding: 0 40px;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .footer-note {
            margin-top: 50px;
            padding: 20px 40px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #777;
            font-size: 11px;
        }
    </style>
</head>
<body>

    <div class="header-top">
        <table class="width-100">
            <tr>
                <td>
                    <img src="{{ public_path('images/caartl2.png') }}" class="logo" alt="Logo">
                </td>
                <td class="text-right">
                    <div style="font-size: 20px; font-weight: bold; color: #333;">Caartl</div>
                    <div class="text-muted">Professional vehicle inspection services</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="invoice-banner">
        <h1>Package Invoice</h1>
    </div>

    <table class="info-table">
        <tr>
            <td class="width-50" style="vertical-align: top;">
                <span class="info-title">Invoice To</span>
                <div class="info-data">
                    <strong>{{ $user->name }}</strong><br>
                    Email: {{ $user->email }}<br>
                    Phone: {{ $user->phone }}
                </div>
            </td>
            
            <td class="width-50 text-right" style="vertical-align: top;">
                <table style="width: 100%; text-align: right;">
                    <tr>
                        <td style="padding-bottom: 5px; color: #777;">Date:</td>
                        <td class="bold" style="padding-bottom: 5px;">{{ $date }}</td>
                    </tr>
                    <tr>
                        <td style="color: #777;">Type:</td>
                        <td class="bold">Subscription Package</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="items-container">
        <table class="items">
            <thead>
                <tr>
                    <th width="50%">Description</th>
                    <th width="25%">Duration</th>
                    <th width="25%" class="text-right">Price</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>{{ $package->name }}</strong>
                    </td>
                    <td>
                        <span class="item-type">{{ $package->duration_days }} Days</span>
                    </td>
                    <td class="text-right">{{ $package->price }}</td>
                </tr>
                <tr><td>&nbsp;</td><td></td><td></td></tr>
            </tbody>
        </table>
    </div>

    <div class="totals-section">
        <table class="totals-table">
            <tr>
                <td class="width-60">
                    <div class="text-muted" style="text-align: left;">
                        Thank you for your purchase!<br>
                        This package subscription is valid for {{ $package->duration_days }} days.
                    </div>
                </td>
                <td class="width-40">
                    <table class="width-100">
                        <tr>
                            <td colspan="2" style="padding-top: 10px;">
                                <div class="bg-yellow" style="padding: 12px; display: block;">
                                    <table class="width-100">
                                        <tr>
                                            <td class="text-left bold" style="border: none; color: #333;">TOTAL:</td>
                                            <td class="text-right bold" style="border: none; color: #333; font-size: 18px;">
                                                {{ $package->price }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer-note">
        This is a computer-generated invoice and does not require a physical signature.
        <br>
        Caartl &copy; {{ date('Y') }}. All rights reserved.
    </div>

</body>
</html>