<?php

namespace App\Events;

use App\Models\BookingCommission;
use Illuminate\Foundation\Events\Dispatchable;

class CommissionRejected
{
    use Dispatchable;

    public function __construct(
        public BookingCommission $commission,
        public ?string $notes = "There is a problem with your payment documents. Please re-upload the documents or contact the support team."
    ) {}
}
