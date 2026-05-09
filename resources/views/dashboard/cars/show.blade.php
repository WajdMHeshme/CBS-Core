@extends('dashboard.layout')

@section('content')
<div class="container mx-auto p-6 max-w-6xl">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

        <div>
            <h1 class="text-3xl font-extrabold text-gray-900">
                {{ $car->title }}
            </h1>
            <p class="text-gray-500 text-sm mt-1">
                {{ $car->brand }} • {{ $car->model }} • {{ $car->year }}
            </p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('dashboard.admin.cars.edit', $car->id) }}"
                class="px-5 py-2.5 bg-yellow-100 text-yellow-800 rounded-full font-semibold hover:bg-yellow-200 transition">
                Edit
            </a>

            <a href="{{ route('dashboard.admin.cars.index') }}"
                class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-full font-semibold hover:bg-gray-200 transition">
                Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left Section --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Description --}}
            <div class="bg-white p-6 rounded-2xl shadow">
                <h3 class="text-lg font-semibold mb-2">Description</h3>
                <p class="text-gray-600 leading-relaxed">
                    {{ $car->description ?? 'No description available.' }}
                </p>
            </div>

            {{-- Images --}}
            <div class="bg-white p-6 rounded-2xl shadow">
                <h3 class="text-lg font-semibold mb-4">Images</h3>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @forelse($car->images as $img)
                    <img src="{{ Storage::url($img->path) }}"
                        class="h-32 w-full object-cover rounded-xl cursor-pointer hover:scale-[1.02] transition"
                        onclick="openLightbox(this.src)">
                    @empty
                    <p class="text-gray-500 col-span-full">No images available</p>
                    @endforelse
                </div>
            </div>

            {{-- Amenities --}}
            <div class="bg-white p-6 rounded-2xl shadow">
                <h3 class="text-lg font-semibold mb-4">Amenities</h3>

                <div class="flex flex-wrap gap-2">
                    @forelse($car->amenities as $a)
                    <span class="px-4 py-1 bg-gray-100 rounded-full text-sm text-gray-700">
                        {{ $a->name }}
                    </span>
                    @empty
                    <p class="text-gray-500">No amenities</p>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- Right Section --}}
        <div class="space-y-6">

            {{-- Price Card --}}
            <div class="bg-white p-6 rounded-2xl shadow text-center">
                <p class="text-sm text-gray-500">Price per day</p>
                <p class="text-3xl font-extrabold text-green-600 mt-2">
                    ${{ number_format($car->price_per_day, 2) }}
                </p>
            </div>

            {{-- Specs --}}
            <div class="bg-white p-6 rounded-2xl shadow space-y-3">
                <h3 class="font-bold border-b pb-2">Specs</h3>

                <p><span class="text-gray-500">Brand:</span> {{ $car->brand }}</p>
                <p><span class="text-gray-500">Model:</span> {{ $car->model }}</p>
                <p><span class="text-gray-500">Year:</span> {{ $car->year }}</p>
                <p><span class="text-gray-500">Status:</span> {{ $car->status }}</p>
            </div>

            {{-- Owner (Admin Only) --}}
            @hasrole('admin')
            <div class="bg-white p-6 rounded-2xl shadow space-y-4">

                <h3 class="font-bold border-b pb-2 flex items-center gap-2 text-gray-800">

                    {{-- Person Icon --}}
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5 text-gray-600"
                        viewBox="0 0 24 24"
                        fill="currentColor">
                        <path d="M12 12c2.76 0 5-2.24 5-5S14.76 2 12 2 7 4.24 7 7s2.24 5 5 5zm0 2c-3.33 0-10 1.67-10 5v3h20v-3c0-3.33-6.67-5-10-5z" />
                    </svg>

                    Owner Info
                </h3>

                <div class="space-y-2 text-sm text-gray-700">
                    <p><span class="text-gray-500">Name:</span> {{ $car->owner?->name }}</p>
                    <p><span class="text-gray-500">Email:</span> {{ $car->owner?->email }}</p>
                    <p><span class="text-gray-500">Role:</span> {{ $car->owner?->getRoleNames()->first() }}</p>
                    <p><span class="text-gray-500">Joined:</span> {{ $car->owner?->created_at?->format('Y-m-d') }}</p>
                </div>

            </div>
            @endhasrole

        </div>
    </div>
</div>

{{-- Lightbox --}}
<div id="lightbox" class="hidden fixed inset-0 bg-black/80 flex items-center justify-center">
    <img id="lightbox-img" class="max-w-3xl rounded-xl">
</div>

<script>
    function openLightbox(src) {
        document.getElementById('lightbox-img').src = src;
        document.getElementById('lightbox').classList.remove('hidden');
    }

    document.getElementById('lightbox').onclick = () => {
        document.getElementById('lightbox').classList.add('hidden');
    };
</script>
@endsection
