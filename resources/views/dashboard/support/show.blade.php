```blade
@extends('dashboard.layout')

@section('content')

<div class="container mx-auto p-6 max-w-4xl">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">

        <h1 class="text-2xl font-bold text-black">
            Ticket #{{ $ticket->id }}
        </h1>

        <a href="{{ route('support.index') }}"
           class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-xl text-sm font-medium transition">
            Back
        </a>

    </div>

    {{-- Card --}}
    <div class="bg-white rounded-2xl shadow-md border p-6 space-y-6">

        {{-- Status --}}
        <div>
            <span class="text-xs px-3 py-1 rounded-full font-semibold
                {{ $ticket->status === 'open' ? 'bg-green-100 text-green-700' : '' }}
                {{ $ticket->status === 'in_progress' ? 'bg-yellow-100 text-yellow-700' : '' }}
                {{ $ticket->status === 'closed' ? 'bg-red-100 text-red-700' : '' }}
            ">
                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
            </span>
        </div>

        {{-- User Info --}}
        <div class="grid md:grid-cols-2 gap-4">

            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-sm text-gray-500 mb-1">Name</p>
                <p class="font-semibold text-gray-800">
                    {{ $ticket->name }}
                </p>
            </div>

            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-sm text-gray-500 mb-1">Email</p>
                <p class="font-semibold text-gray-800 break-all">
                    {{ $ticket->email }}
                </p>
            </div>

            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-sm text-gray-500 mb-1">Phone</p>
                <p class="font-semibold text-gray-800">
                    {{ $ticket->phone }}
                </p>
            </div>

            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-sm text-gray-500 mb-1">Created At</p>
                <p class="font-semibold text-gray-800">
                    {{ $ticket->created_at->format('Y-m-d H:i') }}
                </p>
            </div>

        </div>

        {{-- Subject --}}
        <div>
            <h2 class="text-lg font-bold text-gray-900 mb-2">
                {{ $ticket->subject }}
            </h2>
        </div>

        {{-- Full Message --}}
        <div class="bg-gray-50 rounded-2xl p-5">

            <p class="text-gray-700 leading-loose whitespace-pre-line break-words">
                {{ $ticket->message }}
            </p>

        </div>

    </div>

</div>

@endsection
```
