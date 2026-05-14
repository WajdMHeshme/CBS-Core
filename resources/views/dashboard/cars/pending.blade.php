@extends('dashboard.layout')

@section('content')
<div class="container mx-auto p-6">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-extrabold text-black">
            Pending Cars
        </h1>

        <a href="{{ route('dashboard.admin.cars.index') }}"
            class="px-5 py-2 bg-black text-white rounded-full font-semibold shadow-lg hover:scale-[1.03] transform transition">
            All Cars
        </a>
    </div>

    <form method="GET" class="mb-8 bg-white p-6 rounded-2xl shadow flex flex-col gap-6">

        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label class="block mb-1 text-sm font-medium text-gray-700">Min Price</label>
                <input type="number"
                    name="min_price"
                    value="{{ request('min_price') }}"
                    class="w-full rounded-lg border-gray-300 focus:border-black focus:ring-black">
            </div>

            <div class="flex-1">
                <label class="block mb-1 text-sm font-medium text-gray-700">Max Price</label>
                <input type="number"
                    name="max_price"
                    value="{{ request('max_price') }}"
                    class="w-full rounded-lg border-gray-300 focus:border-black focus:ring-black">
            </div>
        </div>

        <div class="flex justify-between gap-4 flex-col md:flex-row">
            <div class="w-full md:w-[48%]">
                <label class="block mb-2 text-sm font-medium text-gray-700">Car Types</label>

                <select name="car_types[]" multiple class="w-full rounded-xl border-gray-300 h-28 p-2">
                    @foreach($carTypes as $type)
                    <option value="{{ $type->id }}"
                        {{ in_array($type->id, (array) request('car_types', [])) ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="w-full md:w-[48%]">
                <label class="block mb-2 text-sm font-medium text-gray-700">Amenities</label>

                <select name="amenity_ids[]" multiple class="w-full rounded-xl border-gray-300 h-28 p-2">
                    @foreach($amenities as $amenity)
                    <option value="{{ $amenity->id }}"
                        {{ in_array($amenity->id, (array) request('amenity_ids', [])) ? 'selected' : '' }}>
                        {{ $amenity->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('dashboard.admin.cars.pending') }}"
                class="px-5 py-2 bg-gray-200 rounded-full hover:bg-gray-300 transition">
                Reset
            </a>

            <button type="submit"
                class="px-6 py-2 bg-black text-white rounded-full hover:bg-gray-800 transition">
                Apply Filters
            </button>
        </div>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @forelse($cars as $car)
        <div class="border rounded-2xl shadow-lg overflow-hidden bg-white hover:shadow-2xl transition">

            <div class="h-52 bg-gray-100">
                @if($car->mainImage)
                <img src="{{ Storage::url($car->mainImage->path) }}"
                    class="w-full h-52 object-cover"
                    alt="{{ $car->title ?? 'Car image' }}">
                @else
                <div class="flex items-center justify-center h-52 text-gray-400">
                    No image
                </div>
                @endif
            </div>

            <div class="p-4">
                <div class="flex items-center justify-between mb-2">
                    @if($car->carType)
                    <span class="text-xs px-2 py-1 bg-indigo-100 text-black rounded-full">
                        {{ $car->carType->name }}
                    </span>
                    @endif

                    @if($car->approval_status === 'approved')
                    <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full">
                        Approved
                    </span>
                    @elseif($car->approval_status === 'rejected')
                    <span class="text-xs px-2 py-1 bg-red-100 text-red-700 rounded-full">
                        Rejected
                    </span>
                    @else
                    <span class="text-xs px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full">
                        Pending
                    </span>
                    @endif
                </div>

                <div class="flex items-center gap-2 text-sm text-gray-600 mt-1">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4 text-gray-500"
                        fill="currentColor"
                        viewBox="0 0 24 24">
                        <path d="M12 12c2.76 0 5-2.24 5-5S14.76 2 12 2 7 4.24 7 7s2.24 5 5 5zm0 2c-3.33 0-10 1.67-10 5v3h20v-3c0-3.33-6.67-5-10-5z" />
                    </svg>
                    <span>{{ $car->owner?->name ?? 'Unknown' }}</span>
                </div>

                <div class="flex items-center justify-between mb-1 mt-2">
                    <h2 class="text-lg font-semibold">
                        {{ $car->title ?? $car->model ?? 'Car' }}
                    </h2>
                </div>

                <p class="text-green-700 font-medium my-2">
                    ${{ number_format($car->price_per_day, 2) }}
                </p>

                @if($car->rejection_reason)
                <p class="text-sm text-red-600 mb-2">
                    Reject reason: {{ $car->rejection_reason }}
                </p>
                @endif

                <div class="flex gap-2 flex-wrap">
                    <a href="{{ route('dashboard.admin.cars.show', $car->id) }}"
                        class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-sm">
                        View
                    </a>

                    <a href="{{ route('dashboard.admin.cars.edit', $car->id) }}"
                        class="px-3 py-1 bg-yellow-50 text-yellow-700 rounded-full text-sm">
                        Edit
                    </a>

                    <form action="{{ route('dashboard.admin.cars.approve', $car->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <button type="submit"
                            class="px-3 py-1 bg-green-50 text-green-700 rounded-full text-sm">
                            Approve
                        </button>
                    </form>

                    <form action="{{ route('dashboard.admin.cars.reject', $car->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <button type="submit"
                            class="px-3 py-1 bg-red-50 text-red-600 rounded-full text-sm">
                            Reject
                        </button>
                    </form>

                    <form action="{{ route('dashboard.admin.cars.destroy', $car->id) }}" method="POST"
                        onsubmit="event.preventDefault(); window.currentDeleteForm = this; window.dispatchEvent(new CustomEvent('open-modal', { detail: 'delete-car' }));">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center text-gray-500 p-6">
            No pending cars found
        </div>
        @endforelse

    </div>

    <div class="mt-6">
        {{ $cars->appends(request()->query())->links('pagination::tailwind') }}
    </div>
</div>

<x-confirm-modal id="delete-car" title="Delete Car" message="Are you sure you want to delete this car?">
    <button type="button"
        @click="if (window.currentDeleteForm) window.currentDeleteForm.submit(); open = false"
        class="px-5 py-2 rounded-full bg-red-600 text-white hover:bg-red-700 transition">
        Delete
    </button>
</x-confirm-modal>
@endsection
