<?php

namespace App\Events;

use App\Models\LessorRequest;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LessorRequestStatusUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public LessorRequest $lessorRequest,
        public string $status
    ) {}
}
