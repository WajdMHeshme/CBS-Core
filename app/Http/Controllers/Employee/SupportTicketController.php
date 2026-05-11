<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;

class SupportTicketController extends Controller
{

    public function index()
    {
        $tickets = SupportTicket::latest()->get();

        return view('dashboard.employee.support', compact('tickets'));
    }
}
