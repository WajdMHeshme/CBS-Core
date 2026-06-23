<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\User;
use App\Notifications\BookingActionNotification;
use App\Services\BookingService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private BookingService $bookingService
    ) {}

    /**
     * Customer bookings
     */
    public function index(Request $request)
    {
        $bookings = Booking::with([
            'car',
            'employee',
            'user'
        ])
            ->where('user_id', auth('sanctum')->id())

            ->when(
                $request->status,
                fn($q) => $q->where(
                    'status',
                    $request->status
                )
            )

            ->latest()
            ->paginate(10);

        return BookingResource::collection($bookings);
    }

    /**
     * Create booking
     */
    public function store(BookingRequest $request)
    {
        try {

            $validated = $request->validated();

            $booking = $this->bookingService->create(
                $validated,
                auth('sanctum')->id()
            );

            dispatch(function () use ($booking) {
                $this->notifyUsers($booking);
            });

            return response()->json([
                'message' => __('messages.booking.created'),
                'data'    => new BookingResource($booking),
            ], 201);
        } catch (\Exception $e) {

            if ($e->getMessage() === 'CAR_BOOKED') {

                return response()->json([
                    'message' => 'Car is already booked for this period',
                    'booked_periods' => $this->bookingService
                        ->getBookedPeriodsByCar($request->car_id),
                ], 422);
            }

            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Notify admins & employees
     */
    private function notifyUsers(
        Booking $booking,
        string $action = 'created'
    ): void {

        $byUser = $booking->user->name ?? 'Customer';

        $users = User::role([
            'admin',
            'employee'
        ])->get();

        foreach ($users as $user) {

            $user->notify(
                new BookingActionNotification(
                    action: $action,
                    bookingId: $booking->id,
                    byUser: $byUser
                )
            );
        }
    }

    /**
     * Show booking
     */
    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);

        $booking = $this->bookingService->show($booking);

        return new BookingResource($booking);
    }

    /**
     * Cancel booking
     */
    public function cancel(Booking $booking)
    {
        $booking = $this->bookingService->cancel($booking);

        dispatch(fn() => $this->notifyUsers(
            $booking,
            'cancelled'
        ));

        return response()->json([
            'message' => __('messages.booking.canceled'),
            'data'    => new BookingResource($booking),
        ]);
    }
}
