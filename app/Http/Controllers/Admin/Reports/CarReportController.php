<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarReportRequest;
use App\Models\Car;
use App\Models\CarType;
use Barryvdh\DomPDF\Facade\PDF;

class CarReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'check.active', 'role:admin']);
    }

    public function index(CarReportRequest $request)
    {
        $report = $this->getReportData($request->validated());

        $carTypes = CarType::select('id', 'name')->get();

        return view('dashboard.reports.cars', compact('report', 'carTypes'));
    }

    public function export(CarReportRequest $request)
    {
        $report = $this->getReportData($request->validated());

        $carTypes = CarType::select('id', 'name')->get();

        $pdf = PDF::loadView('dashboard.reports.cars-export', compact('report', 'carTypes'));

        return $pdf->download('cars_report_' . now()->format('Y-m-d_H-i-s') . '.pdf');
    }

    private function getReportData(array $filters): array
    {
        $query = Car::query();

        $query->when($filters['status'] ?? null, fn ($q, $status) =>
            $q->where('status', $status)
        );

        $query->when($filters['car_type_id'] ?? null, fn ($q, $type) =>
            $q->where('car_type_id', $type)
        );

        $query->when(
            !empty($filters['from']) && !empty($filters['to']),
            fn ($q) =>
                $q->whereBetween('created_at', [
                    $filters['from'],
                    $filters['to'],
                ])
        );

        return [
            'total_cars' => $query->count(),

            'by_status' => [
                'available'   => Car::where('status', 'available')->count(),
                'booked'      => Car::where('status', 'booked')->count(),
                'maintenance' => Car::where('status', 'maintenance')->count(),
            ],

            'cars' => $query->latest()->get(),
        ];
    }
}
