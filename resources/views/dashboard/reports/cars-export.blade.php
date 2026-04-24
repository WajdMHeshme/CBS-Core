<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Cars Report</title>

    <style>
        body { font-family: sans-serif; color: #333; padding: 25px; margin: 0; }
        .header { border-bottom: 3px solid #111827; padding-bottom: 10px; margin-bottom: 25px; }
        .header h1 { margin: 0; font-size: 22px; text-transform: uppercase; }

        .summary-box { background-color: #f3f4f6; border-radius: 8px; padding: 20px; margin-bottom: 30px; }
        .summary-item { display: inline-block; width: 30%; text-align: center; }
        .summary-label { font-size: 10px; color: #6b7280; font-weight: bold; }
        .summary-value { font-size: 16px; font-weight: bold; margin-top: 5px; }

        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th { background-color: #111827; color: white; padding: 12px 8px; text-align: left; }
        td { padding: 10px 8px; border-bottom: 1px solid #e5e7eb; }
        tr:nth-child(even) { background-color: #f9fafb; }

        .badge { padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; text-transform: uppercase; }

        .available { background-color: #dcfce7; color: #166534; }
        .booked { background-color: #fef9c3; color: #854d0e; }
        .maintenance { background-color: #fee2e2; color: #991b1b; }
    </style>
</head>

<body>

    <div class="header">
        <h1>Cars Inventory Report</h1>
        <p style="font-size: 11px; color: #666;">
            Generated: {{ now()->format('M d, Y | H:i') }}
        </p>
    </div>

    {{-- Summary --}}
    <div class="summary-box">
        <div class="summary-item">
            <div class="summary-label">TOTAL</div>
            <div class="summary-value">{{ $report['total_cars'] }}</div>
        </div>

        <div class="summary-item">
            <div class="summary-label">AVAILABLE</div>
            <div class="summary-value">{{ $report['by_status']['available'] ?? 0 }}</div>
        </div>

        <div class="summary-item">
            <div class="summary-label">BOOKED</div>
            <div class="summary-value">{{ $report['by_status']['booked'] ?? 0 }}</div>
        </div>

        <div class="summary-item">
            <div class="summary-label">MAINTENANCE</div>
            <div class="summary-value">{{ $report['by_status']['maintenance'] ?? 0 }}</div>
        </div>
    </div>

    {{-- Table --}}
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Brand</th>
                <th>Model</th>
                <th>Year</th>
                <th>Price / Day</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>

        <tbody>
            @foreach($report['cars'] as $car)
            <tr>
                <td style="font-weight: bold;">{{ $car->title }}</td>
                <td>{{ $car->brand }}</td>
                <td>{{ $car->model }}</td>
                <td>{{ $car->year }}</td>
                <td>${{ number_format($car->price_per_day, 2) }}</td>

                <td>
                    <span class="badge {{ $car->status }}">
                        {{ $car->status }}
                    </span>
                </td>

                <td>{{ $car->created_at->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
