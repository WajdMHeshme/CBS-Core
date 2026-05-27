<?php

namespace App\Events;

use App\Models\LessorRequest;
use Illuminate\Foundation\Events\Dispatchable;

class LessorRequestCreated
{
    use Dispatchable;

    public function __construct(
        public LessorRequest $lessorRequest
    ) {}
}
