@extends('dashboard.layout')

@section('content')
<div class="container mx-auto p-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-extrabold text-black flex items-center gap-2">
            <!-- Support Icon -->
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M18.364 5.636a9 9 0 11-12.728 0m12.728 0A9 9 0 005.636 18.364M18.364 5.636L12 12m0 0l-6.364-6.364M12 12v9"/>
            </svg>

            {{ __('messages.dashboard.support_tickets') }}
        </h1>
    </div>

    {{-- Tickets Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @forelse($tickets as $ticket)
        <div class="border rounded-2xl bg-white shadow-md hover:shadow-xl transition-all duration-300 p-5 flex flex-col gap-4">

            {{-- Header --}}
            <div class="flex items-center justify-between">

                <div class="flex items-center gap-2">
                    <!-- Ticket Icon -->
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m-6-8h6M5 4h14a2 2 0 012 2v2a2 2 0 01-2 2 2 2 0 000 4 2 2 0 012 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-2a2 2 0 012-2 2 2 0 000-4 2 2 0 01-2-2V6a2 2 0 012-2z"/>
                    </svg>

                    <span class="font-bold text-gray-800">
                        #{{ $ticket->id }}
                    </span>
                </div>

                {{-- Status --}}
                <span class="text-xs px-3 py-1 rounded-full font-semibold
                    {{ $ticket->status === 'open' ? 'bg-green-100 text-green-700' : '' }}
                    {{ $ticket->status === 'in_progress' ? 'bg-yellow-100 text-yellow-700' : '' }}
                    {{ $ticket->status === 'closed' ? 'bg-red-100 text-red-700' : '' }}
                ">
                    {{ ucfirst($ticket->status) }}
                </span>
            </div>

            {{-- User Info --}}
            <div class="bg-gray-50 rounded-xl p-3 space-y-2 text-sm">

                <div class="flex items-center gap-2 text-gray-700">
                    <!-- User -->
                    <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 10a4 4 0 100-8 4 4 0 000 8zM2 18a8 8 0 1116 0H2z"/>
                    </svg>
                    <span class="font-medium">{{ $ticket->name }}</span>
                </div>

                <div class="flex items-center gap-2 text-gray-600">
                    <!-- Email -->
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 12H8m8 0H8m8 0V8a2 2 0 00-2-2H6a2 2 0 00-2 2v8a2 2 0 002 2h10a2 2 0 002-2v-4z"/>
                    </svg>
                    <span>{{ $ticket->email }}</span>
                </div>

                <div class="flex items-center gap-2 text-gray-600">
                    <!-- Phone -->
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h2l2 5-2 1c1 3 3 5 6 6l1-2 5 2v2a2 2 0 01-2 2h-1C8.477 19 3 14.523 3 9V5z"/>
                    </svg>
                    <span>{{ $ticket->phone }}</span>
                </div>

            </div>

            {{-- Subject --}}
            <h3 class="font-semibold text-gray-900 text-base">
                {{ $ticket->subject }}
            </h3>

            {{-- Message --}}
            <p class="text-sm text-gray-500 leading-relaxed">
                {{ \Illuminate\Support\Str::limit($ticket->message, 120) }}
            </p>

            {{-- Footer --}}
            <div class="flex justify-between items-center text-xs text-gray-400 pt-2 border-t">

                <span>
                    {{ $ticket->created_at->format('Y-m-d H:i') }}
                </span>

                <span class="italic">
                    Support System
                </span>

            </div>

        </div>
        @empty
        <div class="col-span-full text-center text-gray-500 p-6">
            No support tickets found
        </div>
        @endforelse

    </div>

</div>
@endsection
