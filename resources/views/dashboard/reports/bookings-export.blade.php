<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Car Bookings Report</title>

    <style>
        body { font-family: sans-serif; color: #333; margin: 0; padding: 20px; }

        .header {
            text-align: center;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .header h1 { margin: 0; font-size: 24px; color: #1e1b4b; }
        .header p { font-size: 12px; color: #6b7280; margin-top: 5px; }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #4f46e5;
            margin-bottom: 10px;
            border-left: 4px solid #4f46e5;
            padding-left: 10px;
        }

        table { width: 100%; border-collapse: collapse; margin-bottom: 25px; font-size: 13px; }

        th {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            color: #374151;
        }

        td {
            border: 1px solid #e5e7eb;
            padding: 10px;
            color: #4b5563;
        }

        .bg-light { background: #fbfbfb; }

        .stats-grid { width: 100%; margin-bottom: 20px; }

        .stats-cell {
            width: 25%;
            border: 1px solid #e5e7eb;
            padding: 15px;
            text-align: center;
        }

        .stats-label {
            font-size: 11px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .stats-value {
            font-size: 18px;
            font-weight: bold;
            color: #111827;
        }

        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
            padding: 10px 0;
            border-top: 1px solid #eee;
        }
    </style>
</head>

<body>

<div class="header">
    <h1>Car Bookings Report</h1>
    <p>Generated on: {{ now()->format('Y-m-d H:i') }}</p>
</div>

{{-- ================= STATS ================= --}}
<div class="section-title">Overview Statistics</div>

<table class="stats-grid">
    <tr>
        <td class="stats-cell">
            <div class="stats-label">Total Bookings</div>
            <div class="stats-value">{{ $stats['total'] ?? 0 }}</div>
        </td>

        <td class="stats-cell">
            <div class="stats-label">Pending</div>
            <div class="stats-value">{{ $stats['pending'] ?? 0 }}</div>
        </td>

        <td class="stats-cell">
            <div class="stats-label">Approved</div>
            <div class="stats-value">{{ $stats['approved'] ?? 0 }}</div>
        </td>

        <td class="stats-cell">
            <div class="stats-label">Completed</div>
            <div class="stats-value">{{ $stats['completed'] ?? 0 }}</div>
        </td>
    </tr>
</table>

{{-- ================= STATUS TABLE ================= --}}
<table>
    <thead>
    <tr>
        <th>Status</th>
        <th>Count</th>
    </tr>
    </thead>

    <tbody>
        <tr><td>Canceled</td><td>{{ $stats['canceled'] ?? 0 }}</td></tr>
        <tr class="bg-light"><td>Rejected</td><td>{{ $stats['rejected'] ?? 0 }}</td></tr>
        <tr><td>Rescheduled</td><td>{{ $stats['rescheduled'] ?? 0 }}</td></tr>
    </tbody>
</table>

{{-- ================= TOP EMPLOYEES ================= --}}
<div class="section-title">Top Employees</div>

<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Bookings</th>
    </tr>
    </thead>

    <tbody>
    @forelse($stats['top_employees'] ?? [] as $emp)
        <tr class="{{ $loop->even ? 'bg-light' : '' }}">
            <td>{{ $emp->employee->name ?? 'N/A' }}</td>
            <td>{{ $emp->total ?? 0 }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="2" style="text-align:center;">No data</td>
        </tr>
    @endforelse
    </tbody>
</table>

{{-- ================= BOOKINGS BY BRAND (cars بدل city) ================= --}}
<div class="section-title">Bookings by Brand</div>

<table>
    <thead>
    <tr>
        <th>Brand</th>
        <th>Total</th>
    </tr>
    </thead>

    <tbody>
    @forelse($stats['by_brand'] ?? [] as $brand)
        <tr class="{{ $loop->even ? 'bg-light' : '' }}">
            <td>{{ $brand->brand ?? 'Unknown' }}</td>
            <td>{{ $brand->total ?? 0 }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="2" style="text-align:center;">No data</td>
        </tr>
    @endforelse
    </tbody>
</table>

<footer>
    Car Rental System | &copy; {{ date('Y') }}
</footer>

</body>
</html>
