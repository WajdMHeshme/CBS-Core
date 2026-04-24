@extends('dashboard.layout')

@section('content')
<div class="container mx-auto p-6 max-w-5xl">
    <div class="bg-white shadow-xl rounded-2xl p-8 space-y-8">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">{{ $car->title }}</h1>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('dashboard.cars.edit', $car->id) }}"
                   class="px-6 py-2.5 bg-yellow-100 text-yellow-800 border rounded-full font-semibold">
                    Edit
                </a>

                <a href="{{ route('dashboard.cars.index') }}"
                   class="px-6 py-2.5 bg-gray-100 text-gray-700 border rounded-full font-semibold">
                    Back
                </a>
            </div>
        </div>

        {{-- Info Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="md:col-span-2 space-y-6">

                {{-- Description --}}
                <div class="bg-gray-50 p-6 rounded-2xl shadow">
                    <h3 class="text-lg font-semibold mb-2">Description</h3>
                    <p class="text-gray-700">
                        {{ $car->description ?? 'No description' }}
                    </p>
                </div>

                {{-- Images --}}
                <div class="bg-gray-50 p-6 rounded-2xl shadow">
                    <h3 class="text-lg font-semibold mb-3">Images</h3>

                    <div class="grid grid-cols-2 gap-3">
                        @forelse($car->images as $img)
                            <img src="{{ asset('storage/'.$img->path) }}"
                                 class="rounded-xl h-32 w-full object-cover cursor-pointer"
                                 onclick="openLightbox(this.src)">
                        @empty
                            <p class="text-gray-500">No images</p>
                        @endforelse
                    </div>
                </div>

                {{-- Amenities --}}
                <div class="bg-gray-50 p-6 rounded-2xl shadow">
                    <h3 class="text-lg font-semibold mb-3">Amenities</h3>

                    @forelse($car->amenities as $a)
                        <span class="px-4 py-2 bg-white border rounded-full text-sm mx-1">
                            {{ $a->name }}
                        </span>
                    @empty
                        <p class="text-gray-500">No amenities</p>
                    @endforelse
                </div>

            </div>

            {{-- Right side --}}
            <div class="space-y-4">

                {{-- Price --}}
                <div class="bg-white p-6 rounded-2xl shadow">
                    <p class="text-sm text-gray-500">Price per day</p>
                    <p class="text-3xl font-extrabold text-green-600">
                        ${{ number_format($car->price_per_day, 2) }}
                    </p>
                </div>

                {{-- Specs --}}
                <div class="bg-white p-6 rounded-2xl shadow space-y-2">
                    <h3 class="font-bold border-b pb-2">Specs</h3>

                    <p><strong>Brand:</strong> {{ $car->brand }}</p>
                    <p><strong>Model:</strong> {{ $car->model }}</p>
                    <p><strong>Year:</strong> {{ $car->year }}</p>
                    <p><strong>Status:</strong> {{ $car->status }}</p>
                </div>

            </div>
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
