@extends('dashboard.layout')

@section('content')

<div class="container mx-auto p-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-extrabold text-black flex items-center gap-2">

            <!-- Icon -->
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m-6-8h6M5 4h14a2 2 0 012 2v2a2 2 0 01-2 2 2 2 0 000 4 2 2 0 012 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-2a2 2 0 012-2 2 2 0 000-4 2 2 0 01-2-2V6a2 2 0 012-2z" />
            </svg>

            Review Details
        </h1>
    </div>

    {{-- Card --}}
    <div class="border rounded-2xl bg-white shadow-md p-6 space-y-5">

        {{-- Top Info --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-sm text-gray-500">User</p>
                <p class="font-semibold text-gray-800 flex items-center gap-2">
                    👤 {{ $review->user->name }}
                </p>

                <p class="font-semibold text-gray-600 flex items-center gap-2">
                  email:   {{ $review->user->email }}
                </p>

            </div>

            <div class="bg-gray-50 rounded-xl p-4 space-y-1">
                <p class="text-sm text-gray-500">Car</p>

                <p class="font-semibold text-gray-800 flex items-center gap-2">
                    🚗 {{ $review->car->brand }}
                </p>

                <p class="text-sm text-gray-600">
                    ID: #{{ $review->car->id }}
                </p>

                <p class="text-sm text-gray-600">
                    Model: {{ $review->car->model ?? 'N/A' }}
                </p>
                <p class="text-sm text-gray-600">
                    Year: {{ $review->car->year ?? 'N/A' }}
                </p>
                <p class="text-sm text-green-600">
                    Price Per Day: {{ $review->car->price_per_day ?? 'N/A' }}
                </p>
            </div>

        </div>

        {{-- Rating + Status --}}
        <div class="flex flex-wrap gap-3 items-center">

            <span class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full text-sm font-bold">
                ⭐ {{ $review->rating }}/5
            </span>

            <span class="text-sm px-4 py-2 rounded-full font-semibold
                {{ $review->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                {{ $review->status === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                {{ $review->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}
            ">
                {{ ucfirst($review->status) }}
            </span>

        </div>

        {{-- Comment --}}
        <div>
            <p class="text-sm text-gray-500 mb-2">Comment</p>
            <div class="bg-gray-50 rounded-xl p-4 text-gray-700 leading-relaxed">
                {{ $review->comment ?? 'No comment provided' }}
            </div>
        </div>

        {{-- Actions --}}
        <div class="pt-4 border-t flex gap-3">

            @if($review->status === 'pending')

            <form action="{{ route('employee.reviews.approve', $review->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <button class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-lg text-sm shadow">
                    Approve
                </button>
            </form>

            <form action="{{ route('employee.reviews.reject', $review->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <button class="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-lg text-sm shadow">
                    Reject
                </button>
            </form>

            @else

            <p class="text-sm text-gray-500 italic">
                No actions available
            </p>

            @endif

        </div>

    </div>

</div>

@endsection
