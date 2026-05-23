<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 28px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            direction: ltr;
            color: #111;
            font-size: 13px;
            line-height: 1.7;
            background: #fff;
        }

        .page {
            border: 1px solid #6C4EFF;
            padding: 22px;
        }

        .top-bar {
            height: 6px;
            background: #6C4EFF;
            margin-bottom: 18px;
        }

        .header {
            text-align: center;
            margin-bottom: 24px;
        }

        .brand {
            font-size: 32px;
            font-weight: bold;
            color: #6C4EFF;
        }

        .doc-title {
            margin-top: 6px;
            font-size: 18px;
            font-weight: bold;
            color: #111;
        }

        .doc-subtitle {
            margin-top: 4px;
            color: #666;
            font-size: 12px;
        }

        .meta {
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            padding: 12px 0;
            margin-bottom: 18px;
        }

        .meta-box {
            display: inline-block;
            width: 48%;
            vertical-align: top;
            margin-bottom: 10px;
        }

        .meta-label {
            font-size: 11px;
            color: #777;
        }

        .meta-value {
            font-size: 13px;
            font-weight: bold;
        }

        .section-title {
            margin: 18px 0 10px;
            padding: 8px 12px;
            background: #6C4EFF;
            color: #fff;
            font-size: 13px;
            font-weight: bold;
        }

        .card {
            border: 1px solid #ddd;
            padding: 14px;
            background: #fafafa;
        }

        .item {
            margin-bottom: 10px;
        }

        .label {
            font-size: 11px;
            color: #666;
        }

        .value {
            font-size: 13px;
            font-weight: bold;
        }

        .amount {
            border: 2px solid #6C4EFF;
            padding: 16px;
            text-align: center;
            margin: 18px 0;
            background: #f4f1ff;
        }

        .amount-title {
            font-size: 12px;
            color: #666;
        }

        .amount-value {
            font-size: 26px;
            font-weight: bold;
            color: #6C4EFF;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            background: #6C4EFF;
            color: #fff;
            font-size: 11px;
            border-radius: 14px;
        }

        .two-cols {
            width: 100%;
            margin-top: 20px;
        }

        .col {
            display: inline-block;
            width: 48%;
            vertical-align: top;
        }

        .box {
            border: 1px solid #111;
            height: 120px;
            padding: 12px;
            position: relative;
        }

        .title-box {
            font-weight: bold;
            color: #6C4EFF;
            margin-bottom: 10px;
        }

        .line {
            position: absolute;
            bottom: 28px;
            left: 12px;
            right: 12px;
            border-top: 1px solid #111;
        }

        .name {
            position: absolute;
            bottom: 8px;
            left: 12px;
            right: 12px;
            text-align: center;
            font-size: 11px;
            color: #666;
        }

        .stamp {
            border: 2px dashed #6C4EFF;
            height: 120px;
            text-align: center;
            padding: 12px;
        }

        .circle {
            width: 70px;
            height: 70px;
            border: 2px solid #6C4EFF;
            border-radius: 50%;
            margin: 10px auto;
            line-height: 70px;
            color: #6C4EFF;
            font-weight: bold;
        }

        .footer {
            margin-top: 18px;
            text-align: center;
            font-size: 11px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>

<body>

<div class="page">

    <div class="top-bar"></div>

    <div class="header">
        <div class="brand">LuxeDrive</div>
        <div class="doc-title">COMMISSION APPROVAL CERTIFICATE</div>
        <div class="doc-subtitle">Official Legal & Financial Document</div>
    </div>

    <div class="meta">
        <div class="meta-box">
            <div class="meta-label">Commission ID</div>
            <div class="meta-value">#{{ $commission->id }}</div>
        </div>

        <div class="meta-box">
            <div class="meta-label">Booking ID</div>
            <div class="meta-value">#{{ $commission->booking_id }}</div>
        </div>

        <div class="meta-box">
            <div class="meta-label">Approved At</div>
            <div class="meta-value">
                {{ $commission->reviewed_at ?? '-' }}
            </div>
        </div>

        <div class="meta-box">
            <div class="meta-label">Status</div>
            <div class="meta-value">
                <span class="status">{{ strtoupper($commission->status) }}</span>
            </div>
        </div>
    </div>

    <div class="section-title">PARTIES INFORMATION</div>

    <div class="card">
        <div class="item">
            <div class="label">Customer</div>
            <div class="value">{{ $customer->name ?? '-' }}</div>
        </div>

        <div class="item">
            <div class="label">Lessor</div>
            <div class="value">{{ $lessor->name ?? '-' }}</div>
        </div>

        <div class="item">
            <div class="label">Approved By</div>
            <div class="value">{{ $employee->name ?? '-' }}</div>
        </div>
    </div>

    <div class="section-title">COMMISSION DETAILS</div>

    <div class="card">
        <div class="item">
            <div class="label">Vehicle / Booking</div>
            <div class="value">Car #{{ $booking->car_id ?? '-' }}</div>
        </div>

        <div class="item">
            <div class="label">Payment Reference</div>
            <div class="value">{{ $commission->payment_reference ?? '-' }}</div>
        </div>

        <div class="item">
            <div class="label">Notes</div>
            <div class="value">{{ $commission->notes ?? 'No notes available' }}</div>
        </div>
    </div>

    <div class="amount">
        <div class="amount-title">Approved Commission Amount</div>
        <div class="amount-value">
            {{ $commission->amount }} {{ $commission->currency }}
        </div>
    </div>

    <div class="two-cols">

        <div class="col">
            <div class="box">
                <div class="title-box">Authorized Signature</div>
                <div class="line"></div>
                <div class="name">{{ $employee->name ?? '-' }}</div>
            </div>
        </div>

        <div class="col">
            <div class="stamp">
                <div class="title-box">Official Stamp</div>
                <div class="circle">SEAL</div>
                <div style="font-size:11px;color:#666;">Company Seal Area</div>
            </div>
        </div>

    </div>

    <div class="section-title">LEGAL DECLARATION</div>

    <div class="card">
        <div class="item">
            <div class="label">Entity</div>
            <div class="value">LuxeDrive Platform</div>
        </div>

        <div class="item">
            <div class="label">Declaration</div>
            <div class="value">
                This document is an official system-generated commission approval record and is legally valid within the platform.
            </div>
        </div>
    </div>

    <div class="footer">
        © LuxeDrive - All Rights Reserved
    </div>

</div>

</body>
</html>
