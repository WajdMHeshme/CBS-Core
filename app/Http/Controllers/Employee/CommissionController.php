<?php

namespace App\Http\Controllers\Employee;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingCommission;
use App\Notifications\CommissionApprovedNotification;
use App\Notifications\CommissionRequestedNotification;
use App\Services\CommissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\CommissionPaymentUploadedNotification;

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

    // إذا العمولة موجودة مسبقًا
    if ($booking->commission()->exists()) {

        $commission = $booking->commission;

        // إعادة إرسال الإشعار
        if ($commission && $commission->lessor) {

            $commission->lessor->notify(
                new CommissionRequestedNotification($commission)
            );
        }

        return back()->with(
            'success',
            'Commission notification resent successfully.'
        );
    }

    $price = $booking->car->price ?? 0;

    $amount = round($price * 0.05, 2);

    $commission = $this->commissionService->createForBooking(
        $booking,
        Auth::user(),
        $amount
    );

    $commission->load([
        'lessor',
        'booking',
    ]);

    // إرسال الإشعار
    if ($commission->lessor) {

        $commission->lessor->notify(
            new CommissionRequestedNotification($commission)
        );
    }

    return back()->with(
        'success',
        'Commission created and notification sent successfully.'
    );
}


    public function uploadPayment(Request $request, BookingCommission $commission)
    {
        if ($commission->lessor_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'payment_reference' => 'nullable|string|max:255',
            'payment_image'     => 'nullable|image|max:2048',
        ]);

        $imagePath = null;

        // رفع صورة الدفع
        if ($request->hasFile('payment_image')) {

            $imagePath = $request->file('payment_image')
                ->store('commissions/payments', 'public');
        }
        $commission = $this->commissionService->uploadPaymentProof(
            $commission,
            $request->payment_reference,
            $imagePath
        );

        // re-fetch لضمان العلاقات
        $commission = BookingCommission::with('employee')->findOrFail($commission->id);

        if ($commission->employee) {
            $commission->employee->notify(
                new CommissionPaymentUploadedNotification($commission)
            );
        }

        return back()->with(
            'success',
            'Payment uploaded successfully.'
        );
    }


    public function approve(BookingCommission $commission)
    {
        $commission = $this->commissionService->approve(
            $commission,
            Auth::user()
        );

        // تحميل العلاقات المطلوبة
        $commission->load([
            'booking.car.carType',
            'booking.car.owner',
            'booking.user',
            'lessor',
            'employee',
        ]);

        $booking = $commission->booking;
        $car = $booking->car;

        // إنشاء PDF
        $pdf = Pdf::loadView(
            'dashboard.lessor.commissions.commission_receipt',
            [
                'commission' => $commission,
                'booking'    => $booking,
                'car'        => $car,
                'customer'   => $booking->user,
                'lessor'     => $commission->lessor,
                'employee'   => Auth::user(),
            ]
        )->setOptions([
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

        // حفظ رابط الملف
        $commission->receipt_pdf = 'commissions/' . $fileName;
        $commission->save();

        // إرسال إشعار للمؤجر
        if ($commission->lessor) {

            $commission->lessor->notify(
                new CommissionApprovedNotification($commission)
            );
        }

        return back()->with(
            'success',
            'Commission approved, PDF generated, and notification sent successfully.'
        );
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
