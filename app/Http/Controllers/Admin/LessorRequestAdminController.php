<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LessorRequest;
use App\Services\LessorRequestService;
use Illuminate\Http\Request;

class LessorRequestAdminController extends Controller
{
    public function index()
    {
        $requests = LessorRequest::with('user')
            ->latest()
            ->get();

        return view('dashboard.lessor-requests.index', compact('requests'));
    }

    public function update(Request $request, LessorRequest $lessorRequest)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        app(LessorRequestService::class)
            ->updateStatus($lessorRequest, $request->status);

        return back()->with('success', 'Updated successfully');
    }
}
