@extends('dashboard.layout')

@section('content')

<div class="container mx-auto p-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-extrabold text-black flex items-center gap-2">

            <!-- Icon -->
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l2.021 6.21a1 1 0 00.95.69h6.533c.969 0 1.371 1.24.588 1.81l-5.29 3.842a1 1 0 00-.364 1.118l2.02 6.21c.3.921-.755 1.688-1.54 1.118l-5.29-3.842a1 1 0 00-1.175 0l-5.29 3.842c-.784.57-1.838-.197-1.539-1.118l2.02-6.21a1 1 0 00-.364-1.118L2.98 11.637c-.783-.57-.38-1.81.588-1.81h6.533a1 1 0 00.95-.69l2.021-6.21z" />
            </svg>

            Reviews Management
        </h1>
    </div>

    {{-- Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @forelse($reviews as $review)

        <div class="border rounded-2xl bg-white shadow-md hover:shadow-xl transition-all duration-300 p-5 flex flex-col gap-4">

            {{-- Header --}}
            <div class="flex items-center justify-between">

                <div class="flex items-center gap-2 text-gray-700 font-bold">
                    <!-- ID icon -->
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
                    </svg>

                    #{{ $review->id }}
                </div>

                {{-- Status --}}
                <span class="text-xs px-3 py-1 rounded-full font-semibold
                    {{ $review->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                    {{ $review->status === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                    {{ $review->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}
                ">
                    {{ ucfirst($review->status) }}
                </span>

            </div>

            {{-- User + Car --}}
            <div class="bg-gray-50 rounded-xl p-3 space-y-2 text-sm">

                <div class="flex items-center gap-2 text-gray-700">
                    <!-- User -->
                    <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 10a4 4 0 100-8 4 4 0 000 8zM2 18a8 8 0 1116 0H2z" />
                    </svg>
                    <span class="font-medium">{{ $review->user->name }}</span>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 space-y-1">
                    <p class="text-sm text-gray-500">Car</p>
                    <p class="text-sm text-gray-600">
                        ID: #{{ $review->car->id }}
                    </p>
                    <p class="font-semibold text-gray-800 flex items-center gap-2">
                        🚗 {{ $review->car->brand }}
                    </p>

                    <p class="text-sm text-gray-600">
                        Model: {{ $review->car->model ?? 'N/A' }}
                    </p>
                    <p class="text-sm text-gray-600">
                        Year: {{ $review->car->year ?? 'N/A' }}
                    </p>

                </div>

            </div>

            {{-- Rating --}}
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-gray-700">Rating</span>

                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-bold">
                    ⭐ {{ $review->rating }}/5
                </span>
            </div>

            {{-- Comment --}}
            <div>
                <p class="text-sm text-gray-500 leading-relaxed">
                    {{ \Illuminate\Support\Str::limit($review->comment, 100) }}
                </p>

                @if(strlen($review->comment) > 100)
                <a href="{{ route('employee.reviews.show', $review->id) }}"
                    class="text-blue-500 text-xs mt-2 inline-block hover:underline">
                    Show more
                </a>
                @endif
            </div>

            {{-- Actions --}}
            <div class="flex gap-2 pt-3 border-t">

                <a href="{{ route('employee.reviews.show', $review->id) }}"
                    class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 rounded-lg text-sm">
                    View
                </a>

                @if($review->status === 'pending')

                <form action="{{ route('employee.reviews.approve', $review->id) }}" method="POST" class="flex-1">
                    @csrf
                    @method('PATCH')
                    <button class="w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg text-sm">
                        Approve
                    </button>
                </form>

                <form action="{{ route('employee.reviews.reject', $review->id) }}" method="POST" class="flex-1">
                    @csrf
                    @method('PATCH')
                    <button class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg text-sm">
                        Reject
                    </button>
                </form>

                @endif

            </div>

        </div>

        @empty

        <div class="col-span-full text-center text-gray-500 p-10">
            No reviews found
        </div>

        @endforelse

    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $reviews->links() }}
    </div>

</div>

@endsection
