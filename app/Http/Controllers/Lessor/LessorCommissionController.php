<?php

namespace App\Http\Controllers\Lessor;

use App\Http\Controllers\Controller;
use App\Models\BookingCommission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessorCommissionController extends Controller
{
    public function index()
    {
        $commissions = BookingCommission::with('booking')
            ->where('lessor_id', Auth::id())
            ->latest()
            ->get();

        return view('dashboard.lessor.commissions.index', compact('commissions'));
    }
    public function pay(Request $request, BookingCommission $commission)
    {
        abort_unless($commission->lessor_id === Auth::id(), 403);

        if ($commission->status === 'paid') {
            return back()->with('error', 'Commission already approved');
        }

        $request->validate([
            'payment_reference' => 'nullable|string|max:255',
            'payment_image' => 'required|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        $path = null;

        if ($request->hasFile('payment_image')) {
            $path = $request->file('payment_image')
                ->store('commission-proofs', 'public');
        }

        $commission->update([
            'payment_reference' => $request->payment_reference,
            'payment_image' => $path,
            'status' => 'payment_uploaded',
        ]);

        return back()->with('success', 'Proof uploaded successfully');
    }

    public function show(BookingCommission $commission)
    {
        abort_unless($commission->lessor_id === Auth::id(), 403);

        return view('dashboard.lessor.commissions.show', compact('commission'));
    }
}
