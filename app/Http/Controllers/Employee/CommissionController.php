<?php

namespace App\Http\Controllers\Employee;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingCommission;
use App\Services\CommissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommissionController extends Controller
{
    public function __construct(
        protected CommissionService $commissionService
    ) {}


    public function index()
    {
        $commissions = BookingCommission::with(['booking.car', 'lessor'])
            ->where('employee_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('dashboard.commissions.index', compact('commissions'));
    }

    public function show($id)
    {
        $commission = BookingCommission::with([
            'booking.car',
            'booking.user',
            'lessor',
            'employee',
        ])->findOrFail($id);

        return view('dashboard.commissions.show', compact('commission'));
    }


    public function requestCommission(Booking $booking)
    {
        // حماية: فقط الموظف المسؤول
        if ($booking->employee_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if ($booking->commission()->exists()) {
            return back()->with('error', 'Commission already exists.');
        }

        $price = $booking->car->price ?? 0;
        $amount = round($price * 0.05, 2);

        $this->commissionService->createForBooking(
            $booking,
            Auth::user(),
            $amount
        );

        return back()->with('success', 'Commission created successfully.');
    }


    public function uploadPayment(Request $request, BookingCommission $commission)
    {
        $request->validate([
            'payment_reference' => 'nullable|string|max:255',
            'payment_image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('payment_image')) {
            $imagePath = $request->file('payment_image')
                ->store('commissions/payments', 'public');
        }

        $this->commissionService->uploadPaymentProof(
            $commission,
            $request->payment_reference,
            $imagePath
        );

        return back()->with('success', 'Payment uploaded successfully.');
    }


public function approve(BookingCommission $commission)
{
    $commission = $this->commissionService->approve(
        $commission,
        Auth::user()
    );

    // تحميل العلاقات بشكل كامل
    $commission->load([
        'booking.car.carType',
        'booking.car.owner',
        'booking.user',
        'lessor',
        'employee',
    ]);

    $booking = $commission->booking;
    $car = $booking->car; // ✅ الحل الأساسي

    $pdf = Pdf::loadView('dashboard.lessor.commissions.commission_receipt', [

        'commission' => $commission,
        'booking'    => $booking,
        'car'        => $car, // ✅ لازم تضيف هذا
        'customer'   => $booking->user,
        'lessor'     => $commission->lessor,
        'employee'   => Auth::user(),

    ])->setOptions([
        'defaultFont' => 'DejaVu Sans',
        'isHtml5ParserEnabled' => true,
        'isRemoteEnabled' => true,
    ]);

    $fileName = 'commission-' . $commission->id . '.pdf';

    $path = storage_path('app/public/commissions');

    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }

    file_put_contents(
        $path . '/' . $fileName,
        $pdf->output()
    );

    $commission->receipt_pdf = 'commissions/' . $fileName;
    $commission->save();

    return back()->with('success', 'Commission approved and PDF generated.');
}

    public function reject(Request $request, BookingCommission $commission)
    {
        $this->commissionService->reject(
            $commission,
            Auth::user(),
            $request->notes
        );

        return back()->with('success', 'Commission rejected.');
    }
}
