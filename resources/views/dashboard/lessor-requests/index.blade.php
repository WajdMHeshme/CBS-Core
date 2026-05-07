@extends('dashboard.layout')

@section('content')
<div class="container mx-auto px-4 py-6">

    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                {{ __('messages.sidebar.lessor_requests') }}
            </h1>

            <p class="text-sm text-gray-500 mt-1">
                Manage requests submitted from the platform
            </p>
        </div>

        <div class="bg-indigo-50 text-indigo-700 px-4 py-2 rounded-xl text-sm font-semibold">
            {{ $requests->count() }} Requests
        </div>
    </div>

    @forelse($requests as $req)

    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6 mb-5 hover:shadow-md transition">

        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">

            {{-- Left --}}
            <div class="flex-1">

                <div class="flex items-center gap-4 mb-5">

                    <div class="w-14 h-14 rounded-full bg-indigo-100 flex items-center justify-center text-xl font-bold text-indigo-700">
                        {{ strtoupper(substr($req->user->name, 0, 1)) }}
                    </div>

                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">
                            {{ $req->user->name }}
                        </h2>

                        <p class="text-sm text-gray-500">
                            {{ $req->user->email }}
                        </p>
                    </div>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs uppercase tracking-wide text-gray-400 mb-1">
                            Status
                        </p>

                        @if($req->status === 'pending')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                            Pending
                        </span>

                        @elseif($req->status === 'approved')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                            Approved
                        </span>

                        @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                            Rejected
                        </span>
                        @endif
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs uppercase tracking-wide text-gray-400 mb-1">
                            Submitted At
                        </p>

                        <p class="text-sm font-medium text-gray-800">
                            {{ $req->created_at->format('Y-m-d h:i A') }}
                        </p>
                    </div>

                </div>

                {{-- Message --}}
                <div class="mt-5 bg-gray-50 rounded-xl p-4">
                    <p class="text-xs uppercase tracking-wide text-gray-400 mb-2">
                        Request Message
                    </p>

                    <p class="text-sm leading-7 text-gray-700">
                        {{ $req->message }}
                    </p>
                </div>

            </div>

            {{-- Actions --}}
            <div class="w-full lg:w-auto">

                @if($req->status === 'pending')

                <form method="POST"
                    action="{{ route('dashboard.lessor-requests.status', $req->id) }}"
                    class="flex flex-col gap-3">

                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        name="status"
                        value="approved"
                        class="px-5 py-3 rounded-xl bg-green-600 hover:bg-green-700 text-white text-sm font-semibold transition">
                        Approve Request
                    </button>

                    <button
                        type="submit"
                        name="status"
                        value="rejected"
                        class="px-5 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition">
                        Reject Request
                    </button>

                </form>

                @else

                <div class="px-4 py-3 rounded-xl bg-gray-100 text-sm text-gray-600 text-center font-medium">
                    Request already processed
                </div>

                @endif

            </div>

        </div>

    </div>

    @empty

    <div class="bg-white rounded-2xl shadow-sm border border-dashed border-gray-300 p-12 text-center">

        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-8 h-8 text-gray-400"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.5">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 6v6l4 2" />
                </svg>
            </div>
        </div>

        <h3 class="text-lg font-semibold text-gray-800 mb-2">
            No Requests Found
        </h3>

        <p class="text-sm text-gray-500">
            There are currently no lessor requests submitted from the platform.
        </p>

    </div>

    @endforelse

</div>
@endsection
