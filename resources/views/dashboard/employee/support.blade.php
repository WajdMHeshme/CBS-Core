@extends('dashboard.layout')

@section('content')
<div class="container mx-auto p-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-extrabold text-black">
            {{ __('messages.dashboard.support_tickets') }}
        </h1>
    </div>

    {{-- Tickets Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @forelse($tickets as $ticket)
        <div class="border rounded-2xl shadow-lg bg-white hover:shadow-2xl transition p-5">

            {{-- Status --}}
            <div class="flex justify-between items-center mb-2">
                <h2 class="font-semibold text-lg">
                    #{{ $ticket->id }}
                </h2>

                <span class="text-xs px-2 py-1 rounded-full
                        {{ $ticket->status === 'open' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $ticket->status === 'in_progress' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $ticket->status === 'closed' ? 'bg-red-100 text-red-700' : '' }}
                    ">
                    {{ ucfirst($ticket->status) }}
                </span>
            </div>

            {{-- Subject --}}
            <h3 class="font-semibold text-gray-800 mb-1">
                {{ $ticket->subject }}
            </h3>

            {{-- Message --}}
            <p class="text-sm text-gray-500 mb-3">
                {{ \Illuminate\Support\Str::limit($ticket->message, 120) }}
            </p>

            {{-- Date --}}
            <p class="text-xs text-gray-400 mb-4">
                {{ $ticket->created_at->format('Y-m-d H:i') }}
            </p>



        </div>
        @empty
        <div class="col-span-full text-center text-gray-500 p-6">
            No support tickets found
        </div>
        @endforelse

    </div>

</div>
@endsection
