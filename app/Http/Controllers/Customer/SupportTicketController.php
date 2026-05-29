<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;

class SupportTicketController extends Controller
{

    public function index()
    {
        $tickets = SupportTicket::latest()->get();

        return view('dashboard.support.index', compact('tickets'));
    }


    public function show($ticket)
    {
        $ticket = SupportTicket::findOrFail($ticket);

        return view('dashboard.support.show', compact('ticket'));
    }
}
