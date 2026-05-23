@extends('dashboard.layout')

@section('content')

<div class="p-6">

    <h2 class="text-xl font-bold mb-4">
        {{ __('messages.sidebar.commissions_requests') }}
    </h2>

    <div class="space-y-4">

        @foreach($commissions as $commission)

            <a href="{{ route('lessor.lessor.commissions.show', $commission->id) }}"
               class="block border rounded-lg p-4 bg-white hover:bg-gray-50">

                <div class="flex justify-between">

                    <div>
                        <div class="font-semibold">
                            Booking #{{ $commission->booking_id }}
                        </div>

                        <div class="text-sm text-gray-500">
                            {{ __('messages.dashboard.amount') }}: {{ $commission->amount }} {{ $commission->currency }}
                        </div>
                    </div>

                    <div>
                        <span class="px-2 py-1 text-xs rounded
                            @if($commission->status == 'requested')
                                bg-yellow-100 text-yellow-700
                            @elseif($commission->status == 'payment_uploaded')
                                bg-blue-100 text-blue-700
                            @elseif($commission->status == 'paid')
                                bg-green-100 text-green-700
                            @endif
                        ">
                            {{ $commission->status }}
                        </span>
                    </div>

                </div>

            </a>

        @endforeach

    </div>

</div>

@endsection
