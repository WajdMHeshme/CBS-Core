<?php

namespace App\Http\Controllers\Employee;

use App\Events\CommissionApproved;
use App\Events\CommissionPaymentUploaded;
use App\Events\CommissionRejected;
use App\Events\CommissionRequested;
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

    /*
    |--------------------------------------------------------------------------
    | LIST COMMISSIONS
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $commissions = BookingCommission::with(['booking.car', 'lessor'])
            ->where('employee_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('dashboard.commissions.index', compact('commissions'));
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW COMMISSION
    |--------------------------------------------------------------------------
    */
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

    /*
    |--------------------------------------------------------------------------
    | REQUEST COMMISSION
    |--------------------------------------------------------------------------
    */
    public function requestCommission(Booking $booking)
    {
        abort_if($booking->employee_id !== Auth::id(), 403);

        $booking->load('car');

        abort_if(!$booking->car?->price_per_day, 500, 'Car price not set');

        $amount = round($booking->car->price_per_day * 0.05, 2);

        $commission = $this->commissionService->createForBooking(
            $booking,
            Auth::user(),
            $amount
        );

        $commission->load(['lessor', 'booking']);

        event(new CommissionRequested($commission));

        return back()->with('success', 'Commission requested successfully');
    }

    /*
    |--------------------------------------------------------------------------
    | UPLOAD PAYMENT
    |--------------------------------------------------------------------------
    */
    public function uploadPayment(Request $request, BookingCommission $commission)
    {
        abort_if($commission->lessor_id !== Auth::id(), 403);

        $data = $request->validate([
            'payment_reference' => 'nullable|string|max:255',
            'payment_image'     => 'nullable|image|max:2048',
        ]);

        $imagePath = $request->file('payment_image')
            ?->store('commissions/payments', 'public');

        $commission = $this->commissionService->uploadPaymentProof(
            $commission,
            $data['payment_reference'],
            $imagePath
        );

        $commission->load(['employee', 'lessor']);

        event(new CommissionPaymentUploaded($commission));

        return back()->with('success', 'Payment uploaded successfully');
    }

    /*
    |--------------------------------------------------------------------------
    | APPROVE COMMISSION
    |--------------------------------------------------------------------------
    */
    // CommissionController.php

    public function approve(BookingCommission $commission) 
    {
        $this->authorizeAction($commission);

        $commission = $this->commissionService->approve(
            $commission,
            Auth::user()
        );

        $commission->load([
            'booking.car',
            'booking.user',
            'lessor',
            'employee',
        ]);

        event(new CommissionApproved($commission));

        return back()->with('success', 'Commission approved successfully');
    }


    /*
    |--------------------------------------------------------------------------
    | REJECT COMMISSION
    |--------------------------------------------------------------------------
    */
    public function reject(Request $request, BookingCommission $commission)
    {
        abort_if($commission->employee_id !== Auth::id(), 403);

        $notes = $request->input('notes');

        $this->commissionService->reject(
            $commission,
            Auth::user(),
            $notes
        );

        $commission = BookingCommission::with(['lessor', 'employee'])->findOrFail($commission->id);

        event(new CommissionRejected($commission, $notes));

        return back()->with('success', 'Commission rejected successfully');
    }
    /*
    |--------------------------------------------------------------------------
    | PRIVATE AUTH CHECK
    |--------------------------------------------------------------------------
    */
    private function authorizeAction(BookingCommission $commission): void
    {
        abort_if($commission->employee_id !== Auth::id(), 403);
    }
}
