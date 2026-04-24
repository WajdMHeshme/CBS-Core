@extends('dashboard.layout')

@section('content')
<div class="container mx-auto p-6 max-w-4xl">
    <div class="bg-white shadow-xl rounded-2xl p-8">
        <h1 class="text-3xl font-extrabold text-indigo-600 mb-6 text-center">
            Edit Car
        </h1>

        <form action="{{ route('dashboard.cars.update', $car->id) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')

            {{-- Title --}}
            <div>
                <label class="block mb-2 font-medium text-gray-700">Title</label>
                <input name="title" value="{{ old('title', $car->title) }}"
                       class="w-full border border-gray-300 rounded-xl p-3">
            </div>

            {{-- Status + Type --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-2 font-medium text-gray-700">Status</label>
                    <select name="status" class="w-full border rounded-xl p-3">
                        <option value="available" {{ $car->status == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="booked" {{ $car->status == 'booked' ? 'selected' : '' }}>Booked</option>
                        <option value="rented" {{ $car->status == 'rented' ? 'selected' : '' }}>Rented</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-2 font-medium text-gray-700">Car Type</label>
                    <select name="car_type_id" class="w-full border rounded-xl p-3">
                        @foreach($carTypes as $type)
                            <option value="{{ $type->id }}"
                                {{ $car->car_type_id == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Price --}}
            <div>
                <label class="block mb-2 font-medium text-gray-700">Price Per Day</label>
                <input type="number" name="price_per_day"
                       value="{{ old('price_per_day', $car->price_per_day) }}"
                       class="w-full border rounded-xl p-3">
            </div>

            {{-- Description --}}
            <div>
                <label class="block mb-2 font-medium text-gray-700">Description</label>
                <textarea name="description" class="w-full border rounded-xl p-3">
                    {{ old('description', $car->description) }}
                </textarea>
            </div>

            {{-- Amenities --}}
            <div>
                <label class="block mb-2 font-medium text-gray-700">Amenities</label>
                <div class="flex flex-wrap gap-3">
                    @foreach($amenities as $a)
                        <label class="border px-3 py-2 rounded">
                            <input type="checkbox" name="amenity_ids[]"
                                   value="{{ $a->id }}"
                                   {{ in_array($a->id, $car->amenities->pluck('id')->toArray()) ? 'checked' : '' }}>
                            {{ $a->name }}
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end gap-4">
                <a href="{{ route('dashboard.cars.index') }}"
                   class="px-6 py-2 bg-gray-200 rounded-full">
                    Cancel
                </a>

                <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-full">
                    Save
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
