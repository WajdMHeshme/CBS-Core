<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingConversation;
use Illuminate\Support\Facades\Auth;

class BookingConversationController extends Controller
{
    public function send(Booking $booking)
    {
        $commission = $booking->commission;

        $message =
            "📢 New Booking Request\n\n" .
            "🚗 Car: {$booking->car->brand} {$booking->car->model}\n" .
            "💰 Commission: {$commission->amount} {$commission->currency}\n\n" .
            "Please pay the commission to unlock customer details.";

        BookingConversation::create([
            'booking_id' => $booking->id,
            'sender_id' => Auth::id(),
            'receiver_id' => $booking->car->user_id,
            'message' => $message,
            'type' => 'system',
        ]);

        return back()->with('success', 'Message sent to lessor.');
    }
}
