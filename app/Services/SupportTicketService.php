<?php

namespace App\Services;

use App\Models\SupportTicket;

class SupportTicketService
{
    public function create(array $data)
    {
        return SupportTicket::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'subject' => $data['subject'],
            'message' => $data['message'],
            'status' => 'open',
        ]);
    }
}
