<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Booking Invoice #{{ $booking->id }}</title>
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
        .mb-20 { margin-bottom: 20px; }
        
        /* --- Colors --- */
        .bg-yellow { background-color: #fff; color: #333; }
        .bg-dark { background-color: #2d3436; color: #fff; }
        .text-yellow { color: #fff; }
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

        /* --- Client & Booking Details --- */
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
        .total-row {
            background-color: #fff;
            padding: 10px;
            font-weight: bold;
            font-size: 16px;
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
        <h1>Booking Invoice</h1>
    </div>

    <table class="info-table">
        <tr>
            <td class="width-50" style="vertical-align: top;">
                <span class="info-title">Billed To / Receiver</span>
                <div class="info-data">
                    <strong>{{ $booking->user->name ?? 'Guest User' }}</strong><br>
                    Receiver: {{ $booking->receiver_name }}<br>
                    Email: {{ $booking->receiver_email }}
                </div>
            </td>
            
            <td class="width-50 text-right" style="vertical-align: top;">
                <table style="width: 100%; text-align: right;">
                    <tr>
                        <td style="padding-bottom: 5px; color: #777;">Booking ID:</td>
                        <td class="bold" style="padding-bottom: 5px;">#{{ $booking->id }}</td>
                    </tr>
                    <tr>
                        <td style="padding-bottom: 5px; color: #777;">Date:</td>
                        <td class="bold" style="padding-bottom: 5px;">{{ $date }}</td>
                    </tr>
                    <tr>
                        <td style="padding-bottom: 5px; color: #777;">Vehicle:</td>
                        <td class="bold" style="padding-bottom: 5px;">
                            {{ $booking->vehicle?->brand?->name ?? '-' }} {{ $booking->vehicle?->vehicleModel?->name ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="color: #777;">Delivery Type:</td>
                        <td class="bold">
                            {{ ucfirst(str_replace('_', ' ', $booking->delivery_type)) }}
                        </td>
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
                    <th width="25%">Category</th>
                    <th width="25%" class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @if($booking->services)
                    @foreach(json_decode($booking->services, true) as $service)
                    <tr>
                        <td>
                            <strong>{{ $service['name'] }}</strong>
                        </td>
                        <td><span class="item-type">Service</span></td>
                        <td class="text-right">{{ number_format($service['price'], 2) }}</td>
                    </tr>
                    @endforeach
                @endif

                @if($booking->fixed_fees)
                    @foreach(json_decode($booking->fixed_fees, true) as $fee)
                    <tr>
                        <td>
                            <strong>{{ $fee['name'] }}</strong>
                        </td>
                        <td><span class="item-type">Fixed Fee</span></td>
                        <td class="text-right">{{ number_format($fee['price'], 2) }}</td>
                    </tr>
                    @endforeach
                @endif
                
                @if(empty($booking->services) && empty($booking->fixed_fees))
                    <tr>
                        <td colspan="3" class="text-center" style="padding: 20px;">No additional charges applied.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="totals-section">
        <table class="totals-table">
            <tr>
                <td class="width-60">
                    <div class="text-muted" style="text-align: left;">
                        Payment Status: <strong>Pending</strong> <br>
                        Thank you for choosing our service.
                    </div>
                </td>
                <td class="width-40">
                    <table class="width-100">
                        <tr>
                            <td colspan="2" style="padding-top: 10px;">
                                <div class="bg-yellow" style="padding: 12px; display: block;">
                                    <table class="width-100">
                                        <tr>
                                            <td class="text-left bold" style="border: none; color: #333;">TOTAL AMOUNT:</td>
                                            <td class="text-right bold" style="border: none; color: #333; font-size: 18px;">
                                                {{ number_format($booking->total_amount, 2) }}
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
        Caartl &copy; {{ date('Y') }}
    </div>

</body>
</html>