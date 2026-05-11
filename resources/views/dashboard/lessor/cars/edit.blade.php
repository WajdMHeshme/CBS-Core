@extends('dashboard.layout')

@section('content')
<div class="container mx-auto p-6 max-w-4xl">
    <div class="bg-white shadow-xl rounded-2xl p-8">
        <h1 class="text-3xl font-extrabold text-black mb-6 text-center">
            Edit Car
        </h1>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
                <ul class="list-disc ml-5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('lessor.cars.update', $car->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block mb-2 font-medium text-gray-700">Title</label>
                <input name="title" value="{{ old('title', $car->title) }}"
                       class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-2 font-medium text-gray-700">Brand</label>
                    <input name="brand" value="{{ old('brand', $car->brand) }}"
                           class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm">
                </div>

                <div>
                    <label class="block mb-2 font-medium text-gray-700">Model</label>
                    <input name="model" value="{{ old('model', $car->model) }}"
                           class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-2 font-medium text-gray-700">Year</label>
                    <input name="year" type="number" value="{{ old('year', $car->year) }}"
                           class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm">
                </div>

                <div>
                    <label class="block mb-2 font-medium text-gray-700">Color</label>
                    <input name="color" value="{{ old('color', $car->color) }}"
                           class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-2 font-medium text-gray-700">Plate Number</label>
                    <input name="plate_number" value="{{ old('plate_number', $car->plate_number) }}"
                           class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm">
                </div>

                <div>
                    <label class="block mb-2 font-medium text-gray-700">Price Per Day</label>
                    <input type="number" step="0.01" name="price_per_day"
                           value="{{ old('price_per_day', $car->price_per_day) }}"
                           class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm">
                </div>
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700">Status</label>
                <select name="status"
                        class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm">
                    <option value="available" {{ old('status', $car->status) == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="maintenance" {{ old('status', $car->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700">Description</label>
                <textarea name="description" rows="5"
                          class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm">{{ old('description', $car->description) }}</textarea>
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700">Car Type</label>
                <select name="car_type_id"
                        class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-indigo-400 focus:outline-none shadow-sm">
                    <option value="">Select Car Type</option>
                    @foreach($carTypes as $type)
                        <option value="{{ $type->id }}"
                            {{ old('car_type_id', $car->car_type_id) == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700">Amenities</label>
                <div class="flex flex-wrap gap-3">
                    @foreach($amenities as $a)
                        <label class="inline-flex items-center bg-gray-100 rounded-xl px-3 py-2 hover:bg-indigo-50 cursor-pointer">
                            <input type="checkbox"
                                   name="amenity_ids[]"
                                   value="{{ $a->id }}"
                                   {{ in_array($a->id, old('amenity_ids', $car->amenities->pluck('id')->toArray())) ? 'checked' : '' }}
                                   class="form-checkbox h-5 w-5 text-black rounded focus:ring-2 focus:ring-indigo-400">
                            <span class="ml-2 text-gray-700">{{ $a->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700">Images</label>
                <input type="file" name="images[]" multiple class="w-full border p-3 rounded-xl">
            </div>

            <div class="flex justify-end gap-4">
                <a href="{{ route('lessor.cars.index') }}"
                   class="px-6 py-3 bg-gray-200 rounded-full text-gray-700 font-semibold hover:bg-gray-300 transition">
                    Cancel
                </a>

                <button type="submit"
                        class="px-6 py-3 bg-black text-white rounded-full font-bold shadow-lg hover:bg-gray-800 transition">
                    Save
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
