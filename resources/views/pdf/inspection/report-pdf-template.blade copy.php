<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Inspection Report #{{ $reportInView->id }}</title>

    {{-- Google Fonts & Font Awesome for Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="shortcut icon" href="{{ public_path('images/favicon@72x.ico') }}">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* --- Customizable CSS Variables --- */
        :root {
            --primary-color: #c9da29;
            --primary-light: rgba(201, 218, 41, 0.15);
            --primary-dark: #a8b622;
            --accent-black: #000000;
            --accent-dark: #1a1a1a;

            --font-family: 'Inter', 'Helvetica', sans-serif;
            --border-color: #e0e0e0;
            --background-light: #f9f9f9;
            --text-dark: #000000;
            --text-muted: #666;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.05);

            /* Enhanced Status Colors */
            --status-excellent-bg: #e8f5e8;
            --status-excellent-text: #2d5a2d;
            --status-good-bg: #e6f7ee;
            --status-good-text: #0a6e3d;
            --status-warning-bg: #fff8e6;
            --status-warning-text: #8a6d3b;
            --status-danger-bg: #fde8e8;
            --status-danger-text: #c53030;
            --status-info-bg: #e6f3ff;
            --status-info-text: #1a5490;
            --status-na-bg: #f5f5f5;
            --status-na-text: #888;
        }

        body {
            font-family: var(--font-family);
            font-size: 12px;
            color: var(--text-dark);
            background-color: #fff;
            line-height: 1.5;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .container {
            width: 100%;
            max-width: 1000px;
            margin: 0;
            padding: 0;
        }


        /* --- Premium Card Sections --- */
        .report-card {
            border: 2px solid var(--primary-color);
            border-radius: 8px;
            margin-bottom: 25px;
            overflow: hidden;
            background: #fff;
            box-shadow: var(--shadow-sm);
        }

        .card-header {
            background: linear-gradient(135deg, var(--accent-black) 0%, var(--accent-dark) 100%);
            color: var(--accent-black);
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 600;
            border-bottom: 3px solid var(--primary-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header .fa-solid {
            color: var(--primary-color);
            font-size: 14px;
        }

        .card-body {
            padding: 2px;
        }

        /* --- Premium Table Layout --- */
        .details-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .details-table tr:nth-child(even) td {
            /* background-color: var(--background-light); */
        }

        .details-table td {
            padding: 12px 15px;
            vertical-align: middle;
            border: none;
            border-radius: 4px;
        }

        .details-table tr:first-child td {
            padding-top: 0;
        }

        /* --- Label/Value Pairs --- */
        .item-label {
            font-weight: 600;
            color: var(--accent-black);
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .item-label .fa-solid {
            font-size: 12px;
            color: var(--primary-color);
            opacity: 1;
        }

        .item-value {
            font-weight: 400;
            font-size: 13px;
            padding: 8px 12px;
            line-height: 1.4;
            background-color: var(--background-light);
            border-radius: 4px;
            border: 1px solid var(--border-color);
        }

        .item-value-list {
            list-style-type: none;
            padding-left: 0;
            margin: 0;
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .item-value-list li {
            background-color: var(--primary-light);
            color: var(--accent-black);
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 500;
            border: 1px solid var(--primary-color);
        }

        /* --- Enhanced Status Pills with Colors --- */
        .status-pill {
            display: inline-flex;
            align-items: center;
            padding: 6px 14px;
            border-radius: 16px;
            font-size: 12px;
            font-weight: 500;
            text-transform: capitalize;
            box-shadow: var(--shadow-sm);
            gap: 6px;
        }

        .status-pill .fa-solid {
            font-size: 10px;
        }

        /* Excellent Status */
        .status-excellent {
            background-color: var(--status-excellent-bg);
            color: var(--status-excellent-text);
        }

        .status-excellent .fa-solid {
            color: var(--status-excellent-text);
        }

        /* Good Status */
        .status-good {
            background-color: var(--status-good-bg);
            color: var(--status-good-text);
        }

        .status-good .fa-solid {
            color: var(--status-good-text);
        }

        /* Warning Status */
        .status-warning {
            background-color: var(--status-warning-bg);
            color: var(--status-warning-text);
        }

        .status-warning .fa-solid {
            color: var(--status-warning-text);
        }

        /* Danger Status */
        .status-danger {
            background-color: var(--status-danger-bg);
            color: var(--status-danger-text);
        }

        .status-danger .fa-solid {
            color: var(--status-danger-text);
        }

        /* Info Status */
        .status-info {
            background-color: var(--status-info-bg);
            color: var(--status-info-text);
        }

        .status-info .fa-solid {
            color: var(--status-info-text);
        }

        /* N/A Status */
        .status-na {
            background-color: var(--status-na-bg);
            color: var(--status-na-text);
        }

        .status-na .fa-solid {
            color: var(--status-na-text);
        }

        /* --- Premium Image Gallery Styles --- */
        .image-gallery {
            padding: 0;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 15px;
        }

        .gallery-item {
            position: relative;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            margin: 5px;
            width: 300px !important;
            box-shadow: var(--shadow-md);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: 2px solid var(--accent-black);
        }

        .gallery-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .gallery-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
        }

        .gallery-caption {
            padding: 12px 15px;
            background: linear-gradient(to right, var(--primary-light), white);
            border-top: 2px solid var(--accent-black);
        }

        .gallery-title {
            font-size: 13px;
            font-weight: 600;
            color: var(--accent-black);
            margin: 0 0 4px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .gallery-title .fa-solid {
            color: var(--primary-color);
            font-size: 12px;
        }

        .gallery-description {
            font-size: 11px;
            color: var(--text-muted);
            margin: 0;
            line-height: 1.4;
        }

        .gallery-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #f0f0f0;
        }

        .gallery-timestamp {
            font-size: 10px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .gallery-category {
            font-size: 10px;
            padding: 3px 8px;
            border-radius: 10px;
            background: var(--accent-black);
            color: var(--primary-color);
            font-weight: 500;
        }

        .no-images {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-muted);
        }

        .no-images .fa-solid {
            font-size: 48px;
            color: var(--border-color);
            margin-bottom: 15px;
        }

        .no-images h3 {
            margin: 0 0 8px 0;
            font-size: 16px;
            font-weight: 500;
        }

        .no-images p {
            margin: 0;
            font-size: 12px;
        }

        /* --- Damage Assessment Styles --- */
        .damage-assessment {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 2px solid var(--accent-black);
        }

        .damage-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .damage-item:last-child {
            border-bottom: none;
        }

        .damage-type {
            font-weight: 500;
            color: var(--text-dark);
        }

        .damage-location {
            color: var(--text-muted);
            font-size: 11px;
        }

        /* --- Premium Footer --- */
        .footer {
            text-align: center;
            margin-top: 40px;
            padding: 15px 0;
            font-size: 10px;
            color: var(--text-muted);
            border-top: 3px solid var(--accent-black);
        }

        .footer-brand {
            color: var(--primary-color);
            font-weight: 700;
            background: var(--accent-black);
            padding: 4px 12px;
            border-radius: 4px;
        }

        /* --- Print Optimization --- */
        @media print {
            body {
                font-size: 11pt;
            }

            .footer {
                position: relative;
            }

            .report-card {
                page-break-inside: avoid;
            }

            .damage-assessment {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .gallery-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 15px;
            }

            .gallery-image {
                height: 150px;
            }

            .gallery-item {
                break-inside: avoid;
            }
        }

        /* --- Full-Page Vertical Centered Header --- */
        .cover-page {
            height: 100vh;
            width: 100%;
            page-break-after: always;
            background: linear-gradient(135deg, var(--accent-black) 0%, var(--accent-dark) 50%, var(--accent-black) 100%);
        }

        .cover-page-table {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
        }

        .cover-page-content {
            vertical-align: middle !important;
            text-align: center !important;
            padding: 40px !important;
        }

        .header-logo {
            margin-top: 300px !important;
            max-width: 250px;
            margin: 40px auto 0 auto;
        }

        .header-title {
            font-size: 36px;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0 0 15px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .header-meta {
            font-size: 14px;
            color: var(--accent-black);
        }

        .header-meta span {
            display: inline-block;
            margin: 0 12px;
            background: rgba(201, 218, 41, 0.1);
            padding: 6px 12px;
            border-radius: 4px;
        }

        .header-meta .fas {
            margin-right: 6px;
        }

        .disclaimer-text {
            text-align: justify;
            line-height: 1.5;
            font-size: 14px;
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Full-Page Cover Header -->
        <div class="cover-page">
            <table class="cover-page-table">
                <tr>
                    <td class="cover-page-content">
                        <h1 class="header-title">Vehicle Inspection Report</h1>

                        <div class="header-meta">
                            <span><i class="fas fa-file-alt"></i> Report #{{ $reportInView->id }}</span>
                            <span><i class="fas fa-calendar"></i> Generated on {{ now()->format('M d, Y g:i A') }}</span>
                            @if($reportInView->vin)
                            <span><i class="fas fa-barcode"></i> VIN: {{ $reportInView->vin }}</span>
                            @endif
                        </div>

                        <div class="header-logo">
                            <img src="{{ public_path('images/caartl.png') }}" alt="Caartl Logo">
                        </div>

                    </td>
                </tr>
            </table>
        </div>
        @php
        // These variables are now only needed for the hardcoded logic inside the template.
        $columnsPerRow = 5;

        // It's still helpful to have the icons in one place.

        // Field icons mapping
        $fieldIcons = [
        'make' => 'fas fa-industry',
        'model' => 'fas fa-car',
        'year' => 'fas fa-calendar-alt',
        'vin' => 'fas fa-barcode',
        'odometer' => 'fas fa-tachometer-alt',
        'color' => 'fas fa-palette',
        'noOfCylinders' => 'fas fa-cog',
        'body_type' => 'fas fa-car-side',
        'registeredEmirates' => 'fas fa-map-marker-alt',
        'specs' => 'fas fa-sliders-h',
        'transmission' => 'fas fa-cogs',
        'horsepower' => 'fas fa-horse-head',
        'warrantyAvailable' => 'fas fa-shield-alt',
        'serviceHistory' => 'fas fa-history',
        'noOfKeys' => 'fas fa-key',
        'mortgage' => 'fas fa-file-invoice-dollar',
        'engine_cc' => 'fas fa-engine',
        'serviceContractAvailable' => 'fas fa-handshake',
        'is_inspection' => 'fas fa-search',
        // Engine fields
        'engineOil' => 'fas fa-oil-can',
        'gearOil' => 'fas fa-cog',
        'gearshifting' => 'fas fa-exchange-alt',
        'engineNoise' => 'fas fa-volume-up',
        'engineSmoke' => 'fas fa-smog',
        'fourWdSystemCondition' => 'fas fa-road',
        'obdError' => 'fas fa-exclamation-triangle',
        'remarks' => 'fas fa-comment',
        // Exterior fields
        'paintCondition' => 'fas fa-brush',
        'convertible' => 'fas fa-car-alt',
        'blindSpot' => 'fas fa-eye',
        'sideSteps' => 'fas fa-stairs',
        'wheelsType' => 'fas fa-circle',
        'rimsSizeFront' => 'fas fa-circle-notch',
        'rimsSizeRear' => 'fas fa-circle-notch',
        // Tires
        'frontLeftTire' => 'fas fa-circle',
        'frontRightTire' => 'fas fa-circle',
        'rearLeftTire' => 'fas fa-circle',
        'rearRightTire' => 'fas fa-circle',
        'tiresSize' => 'fas fa-ruler',
        'spareTire' => 'fas fa-life-ring',
        // Interior
        'speedmeterCluster' => 'fas fa-tachometer-alt',
        'headLining' => 'fas fa-home',
        'seatControls' => 'fas fa-chair',
        'seatsCondition' => 'fas fa-couch',
        'centralLockOperation' => 'fas fa-lock',
        'sunroofCondition' => 'fas fa-sun',
        'windowsControl' => 'fas fa-window-maximize',
        'cruiseControl' => 'fas fa-ship',
        'acCooling' => 'fas fa-snowflake',
        // Car Specs
        'parkingSensors' => 'fas fa-radar',
        'keylessStart' => 'fas fa-key',
        'seats' => 'fas fa-chair',
        'cooledSeats' => 'fas fa-snowflake',
        'heatedSeats' => 'fas fa-fire',
        'powerSeats' => 'fas fa-bolt',
        'viveCamera' => 'fas fa-camera',
        'sunroofType' => 'fas fa-sun',
        'drive' => 'fas fa-road',
        // Brakes
        'steeringOperation' => 'fa-solid fa-dharmachakra',
        'wheelAlignment' => 'fa-solid fa-arrows-to-dot',
        'brakePads' => 'fa-solid fa-compact-disc',
        'suspension' => 'fa-solid fa-car-burst',
        'brakeDiscs' => 'fa-solid fa-compact-disc',
        'shockAbsorberOperation' => 'fa-solid fa-car-burst',
        'comment_section1' => 'fa-solid fa-comments',
        ];
        @endphp

        {{-- Basic Vehicle Information Card - Show ALL fields --}}
        <div class="report-card">
            <div class="card-header"><i class="fa-solid fa-car"></i>Basic Vehicle Information</div>
            <div class="card-body">
                <table class="details-table">
                    <tr>
                        <td>
                            <div class="item-label"><i class="{{ $fieldIcons['make'] ?? 'fas fa-circle-notch' }}"></i> Make </div>
                            <div class="item-value">{{ $reportInView->brand?->name ?? 'N/A' }}</div>

                        </td>
                        <td>
                            <div class="item-label"><i class="{{ $fieldIcons['model'] ?? 'fas fa-circle-notch' }}"></i> Model</div>
                            <div class="item-value">{{ $reportInView->vehicleModel?->name ?? 'N/A' }}</div>
                        </td>
                        <td>
                            <div class="item-label"><i class="{{ $fieldIcons['trim'] ?? 'fas fa-circle-notch' }}"></i> Trim</div>
                            <div class="item-value">{{ $reportInView->trim ?? 'N/A' }}</div>
                        </td>

                        <td>
                            <div class="item-label"><i class="{{ $fieldIcons['year'] ?? 'fas fa-circle-notch' }}"></i> Year</div>
                            <div class="item-value">{{ $reportInView->year ?? 'N/A' }}</div>
                        </td>

                    </tr>
                    <tr>
                        <td>
                            <div class="item-label"><i class="{{ $fieldIcons['vin'] ?? 'fas fa-circle-notch' }}"></i> VIN</div>
                            <div class="item-value">{{ $reportInView->vin ?? 'N/A' }}</div>
                        </td>
                        <td>
                            <div class="item-label"><i class="{{ $fieldIcons['odometer'] ?? 'fas fa-circle-notch' }}"></i> Mileage/Odometer</div>
                            <div class="item-value">{{ $reportInView->odometer.' kms' ?? 'N/A' }}</div>
                        </td>
                        <td>
                            <div class="item-label"><i class="{{ $fieldIcons['engine_cc'] ?? 'fas fa-circle-notch' }}"></i> Engine CC</div>
                            <div class="item-value">{{ $reportInView->engine_cc ?? 'N/A' }}</div>
                        </td>

                        <td>
                            <div class="item-label"><i class="{{ $fieldIcons['horsepower'] ?? 'fas fa-circle-notch' }}"></i> Horsepower</div>
                            <div class="item-value">{{ $reportInView->horsepower ?? 'N/A' }}</div>
                        </td>

                    </tr>
                    <tr>
                        <td>
                            <div class="item-label"><i class="{{ $fieldIcons['color'] ?? 'fas fa-circle-notch' }}"></i> Color</div>
                            <div class="item-value">{{ $reportInView->color ?? 'N/A' }}</div>
                        </td>
                        <td>
                            <div class="item-label"><i class="{{ $fieldIcons['specs'] ?? 'fas fa-circle-notch' }}"></i> Specs</div>
                            <div class="item-value">{{ $reportInView->specs ?? 'N/A' }}</div>
                        </td>

                        <td>
                            <div class="item-label"><i class="{{ $fieldIcons['registeredEmirates'] ?? 'fas fa-circle-notch' }}"></i> Registered Emirates</div>
                            <div class="item-value">{{ $reportInView->registerEmirates ?? 'N/A' }}</div>
                        </td>
                        <td>
                            <div class="item-label"><i class="{{ $fieldIcons['body_type'] ?? 'fas fa-circle-notch' }}"></i> Body Type</div>
                            <div class="item-value">{{ $reportInView->body_type ?? 'N/A' }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="item-label"><i class="{{ $fieldIcons['transmission'] ?? 'fas fa-circle-notch' }}"></i> Transmission</div>
                            <div class="item-value">{{ $reportInView->transmission ?? 'N/A' }}</div>
                        </td>
                        <td>
                            <div class="item-label"><i class="{{ $fieldIcons['warrantyAvailable'] ?? 'fas fa-circle-notch' }}"></i> Warranty Available</div>
                            <div class="item-value">{{ $reportInView->warrantyAvailable ?? 'N/A' }}</div>
                        </td>

                        <td>
                            <div class="item-label"><i class="{{ $fieldIcons['serviceContractAvailable'] ?? 'fas fa-circle-notch' }}"></i> Service Contract</div>
                            <div class="item-value">{{ $reportInView->serviceContractAvailable ?? 'N/A' }}</div>
                        </td>
                        <td>
                            <div class="item-label"><i class="{{ $fieldIcons['serviceHistory'] ?? 'fas fa-circle-notch' }}"></i> Service History</div>
                            <div class="item-value">{{ $reportInView->serviceHistory ?? 'N/A' }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="item-label"><i class="{{ $fieldIcons['noOfKeys'] ?? 'fas fa-circle-notch' }}"></i> No Of Keys</div>
                            <div class="item-value">{{ $reportInView->noOfKeys ?? 'N/A' }}</div>
                        </td>
                        <td>
                            <div class="item-label"><i class="{{ $fieldIcons['mortgage'] ?? 'fas fa-circle-notch' }}"></i> Mortgage</div>
                            <div class="item-value">{{ $reportInView->mortgage ?? 'N/A' }}</div>
                        </td>
                        <td>
                            <div class="item-label"><i class="{{ $fieldIcons['noOfCylinders'] ?? 'fas fa-circle-notch' }}"></i> No. of Cylinders</div>
                            <div class="item-value">{{ $reportInView->noOfCylinders ?? 'N/A' }}</div>
                        </td>
                    </tr>
                    <!-- <tr>
                        <td>
                            <div class="item-label"><i class="{{ $fieldIcons['is_inspection'] ?? 'fas fa-circle-notch' }}"></i> Inspection</div>
                            <div class="item-value">{{ $reportInView->is_inspection ?? 'N/A' }}</div>
                        </td>
                        <td></td>
                    </tr> -->
                </table>
            </div>
        </div>
        {{-- Exterior Section --}}
        <div class="report-card">
            <div class="card-header"><i class="fa-solid fa-brush"></i>Exterior</div>
            <div class="card-body">
                <table class="details-table">
                    {{-- Special full-width row for Paint Condition --}}
                    <tr>
                        <td colspan="{{ $columnsPerRow }}">
                            @php $field = 'paintCondition'; $data = $reportInView->{$field} ?? 'N/A'; $statusInfo = getStatusInfo($data); @endphp
                            <div class="item-label"><i class="{{ $fieldIcons[$field] ?? 'fas fa-circle-notch' }}"></i> {{ Str::of($field)->kebab()->replace('-', ' ')->title() }}</div>
                            @if(is_array($data)) <div class="item-value">
                                <ul class="item-value-list">@foreach($data as $value)<li>{{ $value }}</li>@endforeach</ul>
                            </div>
                            @elseif($statusInfo['class'] !== 'item-value') <div class="status-pill {{ $statusInfo['class'] }}"><i class="{{ $statusInfo['icon'] }}"></i>{{ $data }}</div>
                            @else <div class="item-value">{{ $data }}</div> @endif
                        </td>
                    </tr>
                    <tr>

                    </tr>

                </table>
            </div>
        </div>


        {{-- Damage Assessment Section --}}
        <div class="report-card">
            @if($reportInView->damage_file_path && file_exists(public_path(parse_url($reportInView->damage_file_path, PHP_URL_PATH))))
            @php
            $imageData = base64_encode(file_get_contents(public_path(parse_url($reportInView->damage_file_path, PHP_URL_PATH))));
            $mimeType = mime_content_type(public_path(parse_url($reportInView->damage_file_path, PHP_URL_PATH)));
            @endphp
            <img src="data:{{ $mimeType }};base64,{{ $imageData }}" style="max-width: 100%; height: auto;">
            @else
            <div class="damage-assessment">
                <div class="status-pill status-good">
                    <i class="fas fa-check-circle"></i>
                    No Damage Reported or Image Not Found
                </div>
            </div>
            @endif

        </div>
        {{-- damage summery--}}
        <div class="report-card">
            <div class="card-header">
                <i class="fa-solid fa-car-burst"></i> Damage Assessment Report
            </div>
            <div class="card-body">
                @if($reportInView->damages->count())
                <table class="details-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #333; color: #fff;">
                            <th style="padding: 8px; text-align: left;">#</th>
                            <th style="padding: 8px; text-align: left;">Type</th>
                            <th style="padding: 8px; text-align: left;">Body Part</th>
                            <th style="padding: 8px; text-align: left;">Severity</th>
                            <th style="padding: 8px; text-align: left;">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportInView->damages as $index => $damage)
                        @php
                        $typeInfo = $damageTypes[$damage->type] ?? ['name' => 'Unknown', 'color' => '#999'];
                        $badgeColor = match(strtolower($damage->severity)) {
                        'minor' => '#28a745',
                        'moderate' => '#ffc107',
                        'major', 'severe' => '#dc3545',
                        default => '#17a2b8'
                        };
                        @endphp
                        <tr style="border-bottom: 1px solid #ccc;">
                            <td style="padding: 8px;">{{ $index + 1 }}</td>
                            <td style="padding: 8px;">
                                <span style="
                                    display:inline-block;
                                    width:14px; height:14px;
                                    background:{{ $typeInfo['color'] }};
                                    border-radius:50%;
                                    margin-right:5px;
                                "></span>
                                <strong>{{ strtoupper($damage->type) }}</strong>

                            </td>
                            <td style="padding: 8px;">{{ $damage->body_part }}</td>
                            <td style="padding: 8px;">
                                <span style="
                                    background: {{ $badgeColor }};
                                    color: white;
                                    border-radius: 10px;
                                    padding: 4px 8px;
                                    font-size: 12px;
                                ">
                                    {{ ucfirst($damage->severity) }}
                                </span>
                            </td>
                            <td style="padding: 8px;">{{ $damage->remark ?: 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="damage-assessment">
                    <div class="status-pill status-good">
                        <i class="fas fa-check-circle"></i>
                        No Damages Recorded
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Engine & Transmission Section --}}
        <div class="report-card">
            <div class="card-header"><i class="fa-solid fa-gears"></i>Engine & Transmission</div>
            <div class="card-body">
                <table class="details-table">
                    {{-- Row 1 --}}
                    <tr>
                        @foreach(['engineOil', 'gearOil', 'gearshifting', 'engineNoise', 'engineSmoke'] as $field)
                        <td>
                            <div class="item-label"><i class="{{ $fieldIcons[$field] ?? 'fas fa-circle-notch' }}"></i> {{ Str::of($field)->kebab()->replace('-', ' ')->title() }}</div>
                            @php $data = $reportInView->{$field} ?? 'N/A'; $statusInfo = getStatusInfo($data); @endphp
                            @if(is_array($data)) <div class="item-value">
                                <ul class="item-value-list">@foreach($data as $value)<li>{{ $value }}</li>@endforeach</ul>
                            </div>
                            @elseif($statusInfo['class'] !== 'item-value') <div class="status-pill {{ $statusInfo['class'] }}"><i class="{{ $statusInfo['icon'] }}"></i>{{ $data }}</div>
                            @else <div class="item-value">{{ $data }}</div> @endif
                        </td>
                        @endforeach
                    </tr>
                    {{-- Row 2 --}}
                    <tr>
                        @foreach(['fourWdSystemCondition', 'obdError'] as $field)
                        <td>
                            <div class="item-label"><i class="{{ $fieldIcons[$field] ?? 'fas fa-circle-notch' }}"></i> {{ Str::of($field)->kebab()->replace('-', ' ')->title() }}</div>
                            @php $data = $reportInView->{$field} ?? 'N/A'; $statusInfo = getStatusInfo($data); @endphp
                            @if(is_array($data)) <div class="item-value">
                                <ul class="item-value-list">@foreach($data as $value)<li>{{ $value }}</li>@endforeach</ul>
                            </div>
                            @elseif($statusInfo['class'] !== 'item-value') <div class="status-pill {{ $statusInfo['class'] }}"><i class="{{ $statusInfo['icon'] }}"></i>{{ $data }}</div>
                            @else <div class="item-value">{{ $data }}</div> @endif
                        </td>
                        @endforeach
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    {{-- Full-width row for Remarks --}}
                    <tr>
                        <td colspan="{{ $columnsPerRow }}">
                            @php $field = 'remarks'; $data = $reportInView->{$field} ?? 'N/A'; $statusInfo = getStatusInfo($data); @endphp
                            <div class="item-label"><i class="{{ $fieldIcons[$field] ?? 'fas fa-circle-notch' }}"></i> {{ Str::of($field)->kebab()->replace('-', ' ')->title() }}</div>
                            @if(is_array($data)) <div class="item-value">
                                <ul class="item-value-list">@foreach($data as $value)<li>{{ $value }}</li>@endforeach</ul>
                            </div>
                            @elseif($statusInfo['class'] !== 'item-value') <div class="status-pill {{ $statusInfo['class'] }}"><i class="{{ $statusInfo['icon'] }}"></i>{{ $data }}</div>
                            @else <div class="item-value">{{ $data }}</div> @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>


        {{-- Tires Section --}}
        <div class="report-card">
            <div class="card-header"><i class="fa-solid fa-circle-dot"></i>Tires</div>
            <div class="card-body">
                <table class="details-table">
                    {{-- Row 1 --}}
                    <tr>
                        @foreach(['frontLeftTire', 'frontRightTire', 'rearLeftTire', 'rearRightTire', 'tiresSize'] as $field)
                        <td>
                            <div class="item-label"><i class="{{ $fieldIcons[$field] ?? 'fas fa-circle-notch' }}"></i> {{ Str::of($field)->kebab()->replace('-', ' ')->title() }}</div>
                            @php $data = $reportInView->{$field} ?? 'N/A'; $statusInfo = getStatusInfo($data); @endphp
                            @if(is_array($data)) <div class="item-value">
                                <ul class="item-value-list">@foreach($data as $value)<li>{{ $value }}</li>@endforeach</ul>
                            </div>
                            @elseif($statusInfo['class'] !== 'item-value') <div class="status-pill {{ $statusInfo['class'] }}"><i class="{{ $statusInfo['icon'] }}"></i>{{ $data }}</div>
                            @else <div class="item-value">{{ $data }}</div> @endif
                        </td>
                        @endforeach
                    </tr>
                    {{-- Row 2 --}}
                    <tr>
                        @foreach(['spareTire','wheelsType', 'rimsSizeFront','rimsSizeRear'] as $field)
                        <td>
                            <div class="item-label"><i class="{{ $fieldIcons[$field] ?? 'fas fa-circle-notch' }}"></i> {{ Str::of($field)->kebab()->replace('-', ' ')->title() }}</div>
                            @php $data = $reportInView->{$field} ?? 'N/A'; $statusInfo = getStatusInfo($data); @endphp
                            @if(is_array($data)) <div class="item-value">
                                <ul class="item-value-list">@foreach($data as $value)<li>{{ $value }}</li>@endforeach</ul>
                            </div>
                            @elseif($statusInfo['class'] !== 'item-value') <div class="status-pill {{ $statusInfo['class'] }}"><i class="{{ $statusInfo['icon'] }}"></i>{{ $data }}</div>
                            @else <div class="item-value">{{ $data }}</div> @endif
                        </td>
                        @endforeach
                        <td></td>

                    </tr>
                    {{-- Full-width row for Tire Comments --}}
                    <tr>
                        <td colspan="{{ $columnsPerRow }}">
                            @php $field = 'commentTire'; $data = $reportInView->{$field} ?? 'N/A'; $statusInfo = getStatusInfo($data); @endphp
                            <div class="item-label"><i class="{{ $fieldIcons[$field] ?? 'fas fa-circle-notch' }}"></i> Comments</div>
                            @if(is_array($data)) <div class="item-value">
                                <ul class="item-value-list">@foreach($data as $value)<li>{{ $value }}</li>@endforeach</ul>
                            </div>
                            @elseif($statusInfo['class'] !== 'item-value') <div class="status-pill {{ $statusInfo['class'] }}"><i class="{{ $statusInfo['icon'] }}"></i>{{ $data }}</div>
                            @else <div class="item-value">{{ $data }}</div> @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>


        {{-- Car Specs Section --}}
        <div class="report-card">
            <div class="card-header"><i class="fa-solid fa-sliders"></i>Car Specs</div>
            <div class="card-body">
                <table class="details-table">
                    <tr>
                        @foreach(['parkingSensors', 'keylessStart', 'seats', 'cooledSeats', 'heatedSeats'] as $field)
                        <td>
                            <div class="item-label"><i class="{{ $fieldIcons[$field] ?? 'fas fa-circle-notch' }}"></i> {{ Str::of($field)->kebab()->replace('-', ' ')->title() }}</div>
                            @php $data = $reportInView->{$field} ?? 'N/A'; $statusInfo = getStatusInfo($data); @endphp
                            @if(is_array($data)) <div class="item-value">
                                <ul class="item-value-list">@foreach($data as $value)<li>{{ $value }}</li>@endforeach</ul>
                            </div>
                            @elseif($statusInfo['class'] !== 'item-value') <div class="status-pill {{ $statusInfo['class'] }}"><i class="{{ $statusInfo['icon'] }}"></i>{{ $data }}</div>
                            @else <div class="item-value">{{ $data }}</div> @endif
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach(['powerSeats', 'viveCamera', 'sunroofType', 'drive','blindSpot'] as $field)
                        <td>
                            <div class="item-label"><i class="{{ $fieldIcons[$field] ?? 'fas fa-circle-notch' }}"></i> {{ Str::of($field)->kebab()->replace('-', ' ')->title() }}</div>
                            @php $data = $reportInView->{$field} ?? 'N/A'; $statusInfo = getStatusInfo($data); @endphp
                            @if(is_array($data)) <div class="item-value">
                                <ul class="item-value-list">@foreach($data as $value)<li>{{ $value }}</li>@endforeach</ul>
                            </div>
                            @elseif($statusInfo['class'] !== 'item-value') <div class="status-pill {{ $statusInfo['class'] }}"><i class="{{ $statusInfo['icon'] }}"></i>{{ $data }}</div>
                            @else <div class="item-value">{{ $data }}</div> @endif
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach(['headsDisplay','premiumSound','carbonFiber','convertible','sideSteps'] as $field)
                        <td>
                            <div class="item-label"><i class="{{ $fieldIcons[$field] ?? 'fas fa-circle-notch' }}"></i> {{ Str::of($field)->kebab()->replace('-', ' ')->title() }}</div>
                            @php $data = $reportInView->{$field} ?? 'N/A'; $statusInfo = getStatusInfo($data); @endphp
                            @if(is_array($data)) <div class="item-value">
                                <ul class="item-value-list">@foreach($data as $value)<li>{{ $value }}</li>@endforeach</ul>
                            </div>
                            @elseif($statusInfo['class'] !== 'item-value') <div class="status-pill {{ $statusInfo['class'] }}"><i class="{{ $statusInfo['icon'] }}"></i>{{ $data }}</div>
                            @else <div class="item-value">{{ $data }}</div> @endif
                        </td>
                        @endforeach

                    </tr>
                    <tr>
                        @foreach(['soft_door_closing'] as $field)
                        <td>
                            <div class="item-label"><i class="{{ $fieldIcons[$field] ?? 'fas fa-door-closed' }} text-primary"></i> Soft Door Closing</div>
                            @php $data = $reportInView->{$field} ?? 'N/A'; $statusInfo = getStatusInfo($data); @endphp
                            @if(is_array($data)) <div class="item-value">
                                <ul class="item-value-list">@foreach($data as $value)<li>{{ $value }}</li>@endforeach</ul>
                            </div>
                            @elseif($statusInfo['class'] !== 'item-value') <div class="status-pill {{ $statusInfo['class'] }}"><i class="{{ $statusInfo['icon'] }}"></i>{{ $data }}</div>
                            @else <div class="item-value">{{ $data }}</div> @endif
                        </td>
                        @endforeach
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>


                </table>
            </div>
        </div>


        {{-- Interior, Electrical & Air Conditioner Section --}}
        <div class="report-card">
            <div class="card-header"><i class="fa-solid fa-bolt"></i>Interior, Electrical & Air Conditioner</div>
            <div class="card-body">
                <table class="details-table">
                    {{-- Row 1 --}}
                    <tr>
                        @foreach(['speedmeterCluster', 'headLining', 'seatControls', 'seatsCondition', 'centralLockOperation'] as $field)
                        <td>
                            <div class="item-label"><i class="{{ $fieldIcons[$field] ?? 'fas fa-circle-notch' }}"></i> {{ Str::of($field)->kebab()->replace('-', ' ')->title() }}</div>
                            @php $data = $reportInView->{$field} ?? 'N/A'; $statusInfo = getStatusInfo($data); @endphp
                            @if(is_array($data)) <div class="item-value">
                                <ul class="item-value-list">@foreach($data as $value)<li>{{ $value }}</li>@endforeach</ul>
                            </div>
                            @elseif($statusInfo['class'] !== 'item-value') <div class="status-pill {{ $statusInfo['class'] }}"><i class="{{ $statusInfo['icon'] }}"></i>{{ $data }}</div>
                            @else <div class="item-value">{{ $data }}</div> @endif
                        </td>
                        @endforeach
                    </tr>
                    {{-- Row 2 --}}
                    <tr>
                        @foreach(['sunroofCondition', 'windowsControl', 'cruiseControl', 'acCooling'] as $field)
                        <td>
                            <div class="item-label"><i class="{{ $fieldIcons[$field] ?? 'fas fa-circle-notch' }}"></i> {{ Str::of($field)->kebab()->replace('-', ' ')->title() }}</div>
                            @php $data = $reportInView->{$field} ?? 'N/A'; $statusInfo = getStatusInfo($data); @endphp
                            @if(is_array($data)) <div class="item-value">
                                <ul class="item-value-list">@foreach($data as $value)<li>{{ $value }}</li>@endforeach</ul>
                            </div>
                            @elseif($statusInfo['class'] !== 'item-value') <div class="status-pill {{ $statusInfo['class'] }}"><i class="{{ $statusInfo['icon'] }}"></i>{{ $data }}</div>
                            @else <div class="item-value">{{ $data }}</div> @endif
                        </td>
                        @endforeach
                        <td></td>
                    </tr>
                    {{-- Full-width row for Comments --}}
                    <tr>
                        <td colspan="{{ $columnsPerRow }}">
                            @php $field = 'comment_section2'; $data = $reportInView->{$field} ?? 'N/A'; $statusInfo = getStatusInfo($data); @endphp
                            <div class="item-label"><i class="{{ $fieldIcons[$field] ?? 'fas fa-circle-notch' }}"></i> Comments</div>
                            @if(is_array($data)) <div class="item-value">
                                <ul class="item-value-list">@foreach($data as $value)<li>{{ $value }}</li>@endforeach</ul>
                            </div>
                            @elseif($statusInfo['class'] !== 'item-value') <div class="status-pill {{ $statusInfo['class'] }}"><i class="{{ $statusInfo['icon'] }}"></i>{{ $data }}</div>
                            @else <div class="item-value">{{ $data }}</div> @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>


        {{-- Steering, Suspension & Brakes Section --}}
        <div class="report-card">
            <div class="card-header"><i class="fa-solid fa-car-burst"></i>Steering, Suspension & Brakes</div>
            <div class="card-body">
                <table class="details-table">
                    {{-- Row 1 --}}
                    <tr>
                        @foreach(['steeringOperation', 'wheelAlignment', 'brakePads', 'suspension', 'brakeDiscs'] as $field)
                        <td>
                            <div class="item-label"><i class="{{ $fieldIcons[$field] ?? 'fas fa-circle-notch' }}"></i> {{ Str::of($field)->kebab()->replace('-', ' ')->title() }}</div>
                            @php $data = $reportInView->{$field} ?? 'N/A'; $statusInfo = getStatusInfo($data); @endphp
                            @if(is_array($data)) <div class="item-value">
                                <ul class="item-value-list">@foreach($data as $value)<li>{{ $value }}</li>@endforeach</ul>
                            </div>
                            @elseif($statusInfo['class'] !== 'item-value') <div class="status-pill {{ $statusInfo['class'] }}"><i class="{{ $statusInfo['icon'] }}"></i>{{ $data }}</div>
                            @else <div class="item-value">{{ $data }}</div> @endif
                        </td>
                        @endforeach
                    </tr>
                    {{-- Row 2 --}}
                    <tr>
                        @foreach(['shockAbsorberOperation'] as $field)
                        <td>
                            <div class="item-label"><i class="{{ $fieldIcons[$field] ?? 'fas fa-circle-notch' }}"></i> {{ Str::of($field)->kebab()->replace('-', ' ')->title() }}</div>
                            @php $data = $reportInView->{$field} ?? 'N/A'; $statusInfo = getStatusInfo($data); @endphp
                            @if(is_array($data)) <div class="item-value">
                                <ul class="item-value-list">@foreach($data as $value)<li>{{ $value }}</li>@endforeach</ul>
                            </div>
                            @elseif($statusInfo['class'] !== 'item-value') <div class="status-pill {{ $statusInfo['class'] }}"><i class="{{ $statusInfo['icon'] }}"></i>{{ $data }}</div>
                            @else <div class="item-value">{{ $data }}</div> @endif
                        </td>
                        @endforeach
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    {{-- Full-width row for Comments --}}
                    <tr>
                        <td colspan="{{ $columnsPerRow }}">
                            @php $field = 'comment_section1'; $data = $reportInView->{$field} ?? 'N/A'; $statusInfo = getStatusInfo($data); @endphp
                            <div class="item-label"><i class="{{ $fieldIcons[$field] ?? 'fas fa-circle-notch' }}"></i> Comments</div>
                            @if(is_array($data)) <div class="item-value">
                                <ul class="item-value-list">@foreach($data as $value)<li>{{ $value }}</li>@endforeach</ul>
                            </div>
                            @elseif($statusInfo['class'] !== 'item-value') <div class="status-pill {{ $statusInfo['class'] }}"><i class="{{ $statusInfo['icon'] }}"></i>{{ $data }}</div>
                            @else <div class="item-value">{{ $data }}</div> @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        {{-- Final Conclusion Section --}}
        <div class="report-card">
            <div class="card-header"><i class="fa-solid fa-clipboard"></i>Final Conclusion</div>
            <div class="card-body">
                <table class="details-table">
                    <tr>
                        <td colspan="{{ $columnsPerRow }}">
                            @php $field = 'final_conclusion'; $data = $reportInView->{$field} ?? 'N/A'; $statusInfo = getStatusInfo($data); @endphp
                            <div class="item-label"><i class="{{ $fieldIcons[$field] ?? 'fas fa-flag'  }} text-primary"></i> Final Conclusion</div>
                            @if(is_array($data)) <div class="item-value">
                                <ul class="item-value-list">@foreach($data as $value)<li>{{ $value }}</li>@endforeach</ul>
                            </div>
                            @elseif($statusInfo['class'] !== 'item-value') <div class="status-pill {{ $statusInfo['class'] }}"><i class="{{ $statusInfo['icon'] }}"></i>{{ $data }}</div>
                            @else <div class="item-value">{{ $data }}</div> @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Premium Image Gallery Section --}}
        <div class="report-card">
            <div class="card-header"><i class="fa-solid fa-images"></i>Vehicle Images</div>
            <div class="card-body image-gallery">

                @php
                $vehicleImages = $reportInView->images ?? collect();
                if (!($vehicleImages instanceof \Illuminate\Support\Collection)) {
                $vehicleImages = collect($vehicleImages ?: []);
                }
                $imageNum=1;
                @endphp

                @if($vehicleImages->count())
                <table width="100%" cellspacing="0" cellpadding="8" style="border-collapse: collapse;">
                    @foreach($vehicleImages->chunk(3) as $row)
                    <tr>
                        @foreach($row as $image)
                        <td width="33.33%" valign="top">
                            <div style="margin: 4px;">
                                <img
                                    src="{{ storage_path('app/public/' . $image->path) }}"
                                    alt="{{ $image['title'] ?? 'Vehicle Image' }}"
                                    style="display: block; width: 100%; height: 180px; object-fit: cover; border-radius: 6px;">
                                <div style="margin-top: 6px; border-top: 2px solid #000; padding-top: 6px;">
                                    <div style="font-size: 12px; font-weight: 600; color: #000;">
                                        <i class="fas fa-camera" style="color: #c9da29;"></i>
                                        Vehicle Image #{{$imageNum}}
                                    </div>
                                    <div style="font-size: 10px; color: #666; margin-top: 2px;">
                                        <i class="fas fa-clock"></i>
                                        {{ isset($image->created_at) ? \Carbon\Carbon::parse($image['created_at'])->format('M d, Y') : 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        @php
                        $imageNum++;

                        @endphp
                        @endforeach

                        {{-- Fill remaining cells if row has fewer than 3 items --}}
                        @for($i = $row->count(); $i < 3; $i++)
                            <td width="33.33%">
                            </td>
                            @endfor
                    </tr>
                    @endforeach
                </table>
                @else
                <div class="no-images">
                    <i class="fas fa-image"></i>
                    <h3>No Images Available</h3>
                    <p>No vehicle images have been uploaded for this inspection report.</p>
                </div>
                @endif

            </div>
        </div>

        {{-- Disclaimer Section 
        <div class="report-card">
            <div class="card-body">
                <table class="details-table">
                    <tr>
                        <td>
                            <div class="item-label">Disclaimer </div>
                            <div class="item-value">
                                <p class="disclaimer-text">
                                    The inspection is strictly limited to the items listed in this Inspection Report and does not
                                    cover any other items. 2. The inspection is visual and non-mechanical only. If you wish to
                                    complete a mechanical inspection or an inspection of the internal parts of the vehicle,
                                    Caartl encourages you to contact a different service provider who undertakes that type
                                    of inspection. 3. Caartl does not inspect historic service records or accident records for
                                    the vehicle, and does not check whether the vehicle is subject to a recall notice. 4. While
                                    Caartl uses accepted methods for inspecting the vehicle, these methods do not
                                    necessarily identify all faults with the vehicle. In particular, the inspection does not cover
                                    intermittent problems which are not apparent at the time of the inspection. 5. This
                                    Inspection Report, and all intellectual property rights therein, will remain the exclusive
                                    property of Caartl. 6. This Inspection Report represents Caartl subjective opinion as to
                                    the condition of the vehicle (limited to the specific items listed in this Inspection Report),
                                    considering the age and condition of the vehicle at the time of inspection and based on the
                                    Caartlinspector's knowledge and experience. This Inspection Report is designed to assist
                                    you to make decisions based on the general condition of the vehicle only. Caartl will not
                                    provide a recommendation as to whether to sell or purchase the vehicle. 7. Caartl can
                                    only advise on the condition of the vehicle at the time of the inspection, and this Inspection
                                    Report is only current as at the time it is issued. If you are considering purchasing the
                                    vehicle, it is your responsibility to conduct a further inspection of the vehicle at the time of
                                    purchase. 8. This Inspection Report is provided by Caartl 'as is' for your information only,
                                    without any warranties whatsoever. In particular, Caartl does not provide any warranty
                                    regarding the accuracy or completeness of any information contained in this Inspection
                                    Report, or the fitness of the information contained in this Inspection Report for any purpose
                                    intended. 9. If this Inspection Report is provided to you directly by Caartl, only you may
                                    rely on the content of this Inspection Report, and Caartl does not accept any liability
                                    whatsoever to any third-party you may choose to share this Inspection Report with. 10. If
                                    this Inspection Report is provided to you by someone else than Caartl, you may not rely
                                    on the content of this Inspection Report, and Caartl does not accept any liability
                                    whatsoever to you in connection with this Inspection Report.
                                </p>
                            </div>
                        </td>


                    </tr>
                </table>
            </div>
        </div> --}}

        <div class="footer">
            <span class="footer-brand">Caartl</span> &copy; {{ date('Y') }} | Vehicle Inspection Report
        </div>
    </div>
</body>

</html>