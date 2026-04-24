@extends('dashboard.layout')

@section('content')
@php $isRtl = app()->getLocale() == 'ar'; @endphp

<div class="lg:ms-50 px-6 py-8 space-y-8" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">

    {{-- Title --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900 pb-2">
                Cars Report
            </h1>
            <p class="text-xs text-gray-500">
                Generated at: {{ now()->format('Y-m-d H:i') }}
            </p>
        </div>

        <a href="{{ route('dashboard.reports.cars.export') }}"
           class="px-4 py-2 rounded-xl text-sm font-medium bg-indigo-600 text-white hover:bg-indigo-700 transition shadow-sm">
            Export PDF
        </a>
    </div>

    {{-- Filters --}}
    <form method="GET" class="bg-white border rounded-2xl p-5 shadow-sm grid grid-cols-1 md:grid-cols-4 gap-4">

        {{-- Status --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status" class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-50">
                <option value="">All</option>
                <option value="available" {{ request('status')=='available' ? 'selected' : '' }}>Available</option>
                <option value="booked" {{ request('status')=='booked' ? 'selected' : '' }}>Booked</option>
                <option value="maintenance" {{ request('status')=='maintenance' ? 'selected' : '' }}>Maintenance</option>
            </select>
        </div>

        {{-- Car Type --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Car Type</label>
            <select name="car_type_id" class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-50">
                <option value="">All</option>
                @foreach($carTypes as $type)
                    <option value="{{ $type->id }}" {{ request('car_type_id')==$type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- From --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">From</label>
            <input type="date" name="from" value="{{ request('from') }}" class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-50">
        </div>

        {{-- To --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">To</label>
            <input type="date" name="to" value="{{ request('to') }}" class="w-full border rounded-lg px-3 py-2 text-sm bg-gray-50">
        </div>

        <div class="md:col-span-4 flex {{ $isRtl ? 'justify-start' : 'justify-end' }}">
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-xl text-sm">
                Apply Filters
            </button>
        </div>
    </form>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">

        <div class="bg-white border rounded-xl p-4 text-center">
            <p class="text-xs text-gray-500">Total Cars</p>
            <p class="text-2xl font-bold">{{ $report['total_cars'] }}</p>
        </div>

        <div class="bg-green-50 border rounded-xl p-4 text-center">
            <p class="text-xs text-green-700">Available</p>
            <p class="text-2xl font-bold">{{ $report['by_status']['available'] ?? 0 }}</p>
        </div>

        <div class="bg-yellow-50 border rounded-xl p-4 text-center">
            <p class="text-xs text-yellow-700">Booked</p>
            <p class="text-2xl font-bold">{{ $report['by_status']['booked'] ?? 0 }}</p>
        </div>

        <div class="bg-red-50 border rounded-xl p-4 text-center">
            <p class="text-xs text-red-700">Maintenance</p>
            <p class="text-2xl font-bold">{{ $report['by_status']['maintenance'] ?? 0 }}</p>
        </div>

    </div>

    {{-- Table --}}
    <div class="bg-white border rounded-2xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b bg-gray-50/50">
            <h2 class="font-semibold text-gray-800">Cars List</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">Title</th>
                        <th class="px-4 py-3 text-left">Brand</th>
                        <th class="px-4 py-3 text-left">Model</th>
                        <th class="px-4 py-3 text-left">Year</th>
                        <th class="px-4 py-3 text-left">Price</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Date</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($report['cars'] as $car)
                        <tr class="border-b">
                            <td class="px-4 py-3 font-medium">{{ $car->title }}</td>
                            <td class="px-4 py-3">{{ $car->brand }}</td>
                            <td class="px-4 py-3">{{ $car->model }}</td>
                            <td class="px-4 py-3">{{ $car->year }}</td>
                            <td class="px-4 py-3">${{ $car->price_per_day }}</td>

                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs
                                    {{ $car->status == 'available' ? 'bg-green-100 text-green-700' :
                                       ($car->status == 'booked' ? 'bg-yellow-100 text-yellow-700' :
                                       'bg-red-100 text-red-700') }}">
                                    {{ ucfirst($car->status) }}
                                </span>
                            </td>

                            <td class="px-4 py-3">{{ $car->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 text-gray-400">
                                No cars found
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

</div>
@endsection
