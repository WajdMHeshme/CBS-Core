<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProRequest;
use App\Services\ProRequestService;

class ProRequestController extends Controller
{
    public function __construct(
        protected ProRequestService $service
    ) {}

    public function index()
    {
        $requests = ProRequest::with('user')
            ->latest()
            ->paginate(10);

        return view('admin.pro-requests.index', compact('requests'));
    }

    public function approve(ProRequest $request)
    {
        $this->service->approve($request);

        return back()->with('success', 'Approved successfully');
    }

    public function reject(ProRequest $request)
    {
        $this->service->reject($request, request('reason'));

        return back()->with('success', 'Rejected successfully');
    }
}
