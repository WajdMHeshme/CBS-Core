<?php

namespace App\Events;

use App\Models\BookingCommission;
use Illuminate\Foundation\Events\Dispatchable;

class CommissionRequested
{
    use Dispatchable;

    public function __construct(
        public BookingCommission $commission
    ) {}
}
