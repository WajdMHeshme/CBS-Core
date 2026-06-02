<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        {!! file_get_contents(public_path('pdf/commissions.css')) !!}
    </style>
</head>

@php
$carTitle = $car->title ?? trim(($car->brand ?? '') . ' ' . ($car->model ?? '')) ?: '-';
$carTypeName = optional($car->carType)->name ?? '-';
$ownerName = optional($car->owner)->name ?? '-';
$customerName = $customer->name ?? '-';
$lessorName = $lessor->name ?? '-';
$approvedByName = $employee->name ?? '-';
$approvedAt = $commission->reviewed_at
? \Illuminate\Support\Carbon::parse($commission->reviewed_at)->format('d M Y, h:i A')
: 'Pending';
$statusClass = strtolower($commission->status ?? 'pending');
@endphp

<body>
    <div class="page">

        <div class="watermark">LUXEDRIVE</div>
        <div class="top-bar"></div>

        <header class="header">
            <div class="brand">LuxeDrive</div>
            <div class="doc-title">COMMISSION APPROVAL CERTIFICATE</div>
            <div class="doc-subtitle">Official Financial & Legal Record</div>

            <div class="doc-ref">
                Document Ref: LD-COM-{{ $commission->id }}-{{ date('Y') }}
            </div>
        </header>

        <section class="meta">
            <div class="meta-box">
                <span class="meta-label">Commission ID</span>
                <span class="meta-value">#{{ $commission->id }}</span>
            </div>

            <div class="meta-box">
                <span class="meta-label">Booking ID</span>
                <span class="meta-value">#{{ $commission->booking_id }}</span>
            </div>

            <div class="meta-box">
                <span class="meta-label">Approved Date</span>
                <span class="meta-value">{{ $approvedAt }}</span>
            </div>

            <div class="meta-box status-box">
                <span class="meta-label">Status</span>
                <span class="status status-{{ $statusClass }}">
                    {{ strtoupper($commission->status ?? 'pending') }}
                </span>
            </div>
        </section>

        <div class="divider"></div>

        <section class="section">
            <h2 class="section-title">PARTIES INFORMATION</h2>

            <div class="info-grid">
                <div class="info-card">
                    <h3>Customer</h3>
                    <p>{{ $customerName }}</p>
                </div>

                <div class="info-card">
                    <h3>Lessor</h3>
                    <p>{{ $lessorName }}</p>
                </div>

                <div class="info-card">
                    <h3>Approved By</h3>
                    <p>{{ $approvedByName }}</p>
                </div>
            </div>
        </section>

        <section class="section">
            <h2 class="section-title">COMMISSION DETAILS</h2>

            <div class="details-card">
                <div class="detail-row">
                    <span class="detail-label">Vehicle / Booking</span>
                    <span class="detail-value">Car #{{ $booking->car_id ?? '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Payment Reference</span>
                    <span class="detail-value">{{ $commission->payment_reference ?? '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Notes</span>
                    <span class="detail-value">{{ $commission->notes ?? 'No notes available' }}</span>
                </div>
            </div>
        </section>

        <section class="section">
            <h2 class="section-title">VEHICLE INFORMATION</h2>

            <div class="vehicle-card">
                <div class="vehicle-head">
                    <div class="vehicle-title">{{ $carTitle }}</div>
                    <div class="vehicle-badge">{{ $carTypeName }}</div>
                </div>

                <table class="vehicle-table">
                    <tbody>
                        <tr>
                            <th>Brand</th>
                            <td>{{ $car->brand ?? '-' }}</td>
                            <th>Model</th>
                            <td>{{ $car->model ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Year</th>
                            <td>{{ $car->year ?? '-' }}</td>
                            <th>Color</th>
                            <td>{{ $car->color ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Seats</th>
                            <td>{{ $car->seats ?? '-' }}</td>
                            <th>Plate Number</th>
                            <td>{{ $car->plate_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Car Type</th>
                            <td>{{ $carTypeName }}</td>
                            <th>Owner</th>
                            <td>{{ $ownerName }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="amount-box">
            <div class="amount-title">APPROVED COMMISSION</div>
            <div class="amount-value">
                {{ number_format((float) $commission->amount, 2) }} {{ $commission->currency }}
            </div>
        </section>

        <section class="signatures">
            <div class="sig-box">
                <div class="sig-title">Authorized Signature</div>
                <div class="sig-line"></div>
                <div class="sig-name">{{ $approvedByName }}</div>
            </div>

            <div class="stamp-box">
                <div class="sig-title">Official Seal</div>
                <div class="seal">APPROVED</div>
            </div>
        </section>

        <section class="section">
            <h2 class="section-title">LEGAL DECLARATION</h2>

            <div class="legal-card">
                <p><strong>Entity:</strong> LuxeDrive Platform</p>
                <p>
                    This document is an official system-generated financial record.
                    It is valid for audit, verification, and legal reference within the platform ecosystem.
                </p>
            </div>
        </section>

        <footer class="footer">
            <div>© {{ date('Y') }} LuxeDrive. All Rights Reserved.</div>
            <div class="small">Confidential Financial Document</div>
        </footer>

    </div>
</body>

</html>
