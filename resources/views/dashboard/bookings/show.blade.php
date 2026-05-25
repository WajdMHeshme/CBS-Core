@extends('dashboard.layout')

@section('content')

<div class="lg:ml-50 px-6 py-8 {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">
            {{ __('messages.booking.booking_details') }}
        </h2>

        <p class="text-sm text-gray-500 mt-1">

            {{ app()->getLocale() == 'ar' ? 'معلومات الحجز الكاملة وإجراءات الإدارة' : 'Full booking information & management actions' }}
        </p>
    </div>

    {{-- Grid Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left Column: Booking Details --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Card --}}
            <div class="p-6 bg-white border rounded-xl shadow-sm">

                {{-- Top Row : Booking ID + Status --}}
                <div class="flex items-center justify-between mb-6">

                    <h3 class="text-lg font-semibold">
                        {{ __('messages.reports.bookings') }} #{{ $booking->id }}
                    </h3>

                    <span class="px-3 py-1 rounded-lg text-xs border
                        @if($booking->status == 'pending')
                            bg-yellow-50 text-yellow-700 border-yellow-300
                        @elseif($booking->status == 'approved')
                            bg-green-50 text-green-700 border-green-300
                        @elseif($booking->status == 'rejected')
                            bg-red-50 text-red-700 border-red-300
                        @elseif($booking->status == 'canceled' || $booking->status == 'cancelled')
                            bg-gray-100 text-gray-700 border-gray-300
                        @elseif($booking->status == 'completed')
                            bg-blue-50 text-blue-700 border-blue-300
                        @endif">
                        {{ __('messages.status.' . $booking->status) }}
                    </span>

                </div>


                {{-- Grid Layout for Details --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Customer --}}
                    <div class="p-4 border rounded-xl">
                        <h4 class="font-medium text-gray-800 mb-2">{{ __('messages.booking.customer') }}</h4>

                        <div class="flex items-start gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-black" viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd" d="M12 2.25a4.5 4.5 0 014.5 4.5v.75a4.5 4.5 0 11-9 0V6.75a4.5 4.5 0 014.5-4.5zm-7.5 17.1a7.5 7.5 0 0115 0v.15A2.25 2.25 0 0117.25 21h-10.5A2.25 2.25 0 014.5 19.5v-.15z" clip-rule="evenodd" />
                            </svg>

                            <div>
                                <p class="text-sm font-medium text-gray-700">
                                    {{ $booking->user->name ?? __('messages.reports.unknown') }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $booking->user->email ?? '' }}
                                </p>

                            </div>
                        </div>
                    </div>
                    {{-- Customer --}}
                    <div class="p-4 border rounded-xl">
                        <h4 class="font-medium text-gray-800 mb-2">{{ __('messages.user.role_lessor') }}</h4>

                        <div class="flex items-start gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-black" viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd" d="M12 2.25a4.5 4.5 0 014.5 4.5v.75a4.5 4.5 0 11-9 0V6.75a4.5 4.5 0 014.5-4.5zm-7.5 17.1a7.5 7.5 0 0115 0v.15A2.25 2.25 0 0117.25 21h-10.5A2.25 2.25 0 014.5 19.5v-.15z" clip-rule="evenodd" />
                            </svg>

                            <div>
                                <p class="text-sm font-medium text-gray-700">
                                <p class="font-semibold text-primary py-2">
                                    {{ $booking->car->owner?->name ?? 'Unknown Lessor' }}
                                </p>
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $booking->car->owner?->email ?? '' }}
                                </p>

                            </div>
                        </div>
                    </div>


                    <div class="p-4 border rounded-xl">
                        <h4 class="font-medium text-gray-800 mb-2">{{ __('messages.booking.car') }}</h4>
                        <p class="text-sm font-semibold">
                            {{ $booking->car->brand ?? __('messages.reports.unknown') }} {{ $booking->car->model ?? '' }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $booking->car->year ?? '' }} — {{ $booking->car->plate_number ?? '' }}
                        </p>
                    </div>


                    {{-- Schedule --}}
                    <div class="p-4 border rounded-xl">
                        <h4 class="font-medium text-gray-800 mb-2">{{ __('messages.booking.scheduled_visit') }}</h4>

                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-black" viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd" d="M6.75 2.25a.75.75 0 01.75.75V4.5h9V3a.75.75 0 011.5 0v1.5h.75A2.25 2.25 0 0121 6.75v12A2.25 2.25 0 0118.75 21H5.25A2.25 2.25 0 013 18.75v-12A2.25 2.25 0 015.25 4.5H6V3a.75.75 0 01.75-.75zM3.75 9h16.5v9.75a.75.75 0 01-.75.75H4.5a.75.75 0 01-.75-.75V9z" clip-rule="evenodd" />
                            </svg>

                            <p class="text-sm text-gray-700">
                                {{ $booking->scheduled_at }}
                            </p>
                        </div>
                    </div>


                    {{-- Employee --}}
                    @if($booking->employee)
                    <div class="p-4 border rounded-xl">
                        <h4 class="font-medium text-gray-800 mb-2">{{ __('messages.booking.assigned_employee') }}</h4>

                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-black" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M9 2.25h6A2.25 2.25 0 0117.25 4.5V6H6.75V4.5A2.25 2.25 0 019 2.25z" />
                                <path fill-rule="evenodd" d="M3.75 7.5A2.25 2.25 0 016 5.25h12A2.25 2.25 0 0120.25 7.5v9A2.25 2.25 0 0118 18.75H6A2.25 2.25 0 013.75 16.5v-9z" clip-rule="evenodd" />
                            </svg>

                            <p class="text-sm text-gray-500">
                                {{ $booking->employee->name }}
                            </p>
                        </div>
                    </div>
                    @endif

                </div>
                {{-- Commission --}}
                @if($booking->commission)

                <div class="p-5 border rounded-xl mt-4 bg-white">

                    <div class="flex items-center justify-between mb-4">

                        <h4 class="font-semibold text-gray-900">
                            {{ __('messages.dashboard.commission') }}
                        </h4>

                        <span class="px-3 py-1 text-xs rounded-full border
            @if($booking->commission->status == 'pending')
                bg-yellow-50 text-yellow-700 border-yellow-200
            @elseif($booking->commission->status == 'payment_uploaded')
                bg-blue-50 text-blue-700 border-blue-200
            @elseif($booking->commission->status == 'paid')
                bg-green-50 text-green-700 border-green-200
            @elseif($booking->commission->status == 'rejected')
                bg-red-50 text-red-700 border-red-200
            @endif">

                            {{ $booking->commission->status }}

                        </span>

                    </div>

                    <div class="space-y-2 text-sm">

                        <p>
                            <span class="text-gray-500">
                                {{ __('messages.dashboard.amount') }}:
                            </span>

                            <span class="font-medium">
                                {{ $booking->commission->amount }}
                                {{ $booking->commission->currency }}
                            </span>
                        <div>

                            <span class="text-gray-500">
                                {{ __('messages.dashboard.payment_reference') }}:
                            </span>

                            <span class="font-medium">
                                ({{ $booking->commission->payment_reference ?? (app()->getLocale() == 'ar' ? 'لا يوجد مرجع دفع' : 'No payment reference') }})
                            </span>
                        </div>

                        </p>

                    </div>

                    @if($booking->commission->payment_image)

                    <div class="mt-4">

                        <p class="text-sm text-gray-500 mb-2">
                            Payment Proof
                        </p>

                        {{-- Thumbnail --}}
                        <img
                            src="{{ asset('storage/' . $booking->commission->payment_image) }}"
                            onclick="openPaymentModal()"
                            class="w-32 h-32 object-cover rounded-lg border cursor-pointer hover:opacity-80 transition">

                    </div>

                    {{-- Modal --}}
                    <div id="paymentModal"
                        class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50 p-4">

                        {{-- Close --}}
                        <button onclick="closePaymentModal()"
                            class="absolute top-5 right-5 text-white text-3xl">
                            ×
                        </button>

                        {{-- Full Image --}}
                        <img
                            src="{{ asset('storage/' . $booking->commission->payment_image) }}"
                            class="max-w-full max-h-[90vh] rounded-xl shadow-2xl">

                    </div>

                    <script>
                        function openPaymentModal() {
                            document.getElementById('paymentModal')
                                .classList.remove('hidden');

                            document.getElementById('paymentModal')
                                .classList.add('flex');
                        }

                        function closePaymentModal() {
                            document.getElementById('paymentModal')
                                .classList.remove('flex');

                            document.getElementById('paymentModal')
                                .classList.add('hidden');
                        }
                    </script>

                    @endif

                    {{-- Employee Actions --}}
                    @if(
                    auth()->user()->hasRole('employee') &&
                    $booking->commission->status === 'payment_uploaded'
                    )

                    <div class="flex gap-2 mt-5">

                        <form method="POST"
                            action="{{ route('employee.commissions.approve', $booking->commission->id) }}">
                            @csrf

                            <button class="px-4 py-3 rounded-lg bg-green-500 text-white text-sm">
                                Approve Payment
                            </button>
                        </form>

                        {{-- Reject --}}
                        <form method="POST"
                            action="{{ route('employee.commissions.reject', $booking->commission->id) }}">

                            @csrf
                            @method('POST')

                            <button class="px-4 py-2 border border-red-200 text-red-600 rounded-lg hover:bg-red-50 transition">
                                Reject
                            </button>

                        </form>

                    </div>

                    @endif

                </div>

                @endif

                {{-- Rejection Reason --}}
                @if($booking->status == 'rejected')
                <div class="mt-4 p-4 border border-red-200 bg-red-50 rounded-xl">
                    <h4 class="font-medium text-red-800 mb-1">{{ app()->getLocale() == 'ar' ? 'سبب الرفض' : 'Rejection Reason' }}</h4>
                    <p class="text-sm text-red-700">
                        {{ $booking->rejection_reason ?? (app()->getLocale() == 'ar' ? 'لم يتم تسجيل سبب للرفض.' : 'No reason recorded.') }}
                    </p>
                </div>
                @endif

                {{-- Notes --}}
                @if($booking->notes)
                <div class="mt-4 p-4 border rounded-xl">
                    <h4 class="font-medium text-gray-800 mb-1">{{ __('messages.booking.notes') }}</h4>
                    <p class="text-sm text-gray-600">
                        {{ $booking->notes }}
                    </p>
                </div>
                @endif

                {{-- Review Section --}}
                <div class="mt-4 p-4 border rounded-xl">
                    <h4 class="font-medium text-gray-800 mb-2">{{ app()->getLocale() == 'ar' ? 'تقييم العميل' : 'Customer Review' }}</h4>
                    @if ($booking->status !== 'completed')
                    <span class="text-sm text-gray-400">
                        {{ __('messages.booking.review_after_completion') }}
                    </span>
                    @elseif (! $booking->review)
                    <span class="text-sm text-gray-500 font-medium">
                        {{ __('messages.booking.awaiting_customer_review') }}
                    </span>
                    @else
                    <div class="flex flex-col gap-1">
                        <div class="flex items-center">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="text-lg">{{ $i <= $booking->review->rating ? '⭐' : '☆' }}</span>
                                @endfor
                        </div>
                        <span class="text-sm text-gray-700 italic">
                            "{{ $booking->review->comment }}"
                        </span>
                        <span class="text-xs text-gray-500">
                            {{ __('messages.reports.employee') }}: {{ $booking->review->user->name }}
                        </span>
                    </div>
                    @endif
                </div>
                @php
                $isOwner = $booking->employee_id === auth()->id();
                $status = $booking->status;
                @endphp
                {{-- Actions --}}
                <div class="mt-6 flex flex-wrap items-center gap-2">
                    @if(
                    $isOwner &&
                    $status === 'approved' &&
                    $booking->commission &&
                    $booking->commission->status !== 'paid'
                    )
                    @endif
                    @if($isOwner && $status === 'approved')
                    <form method="POST" action="{{ route('employee.commissions.request', $booking->id) }}">
                        @csrf
                        <button class="px-3 py-1 rounded-full text-sm bg-purple-50 border border-purple-200 text-purple-700 hover:bg-purple-100">
                            {{ __('messages.dashboard.send_message_to_lessor') }} </button>
                    </form>
                    @endif
                    <a href="{{ auth()->user()->hasRole('admin') ? route('employee.bookings.index') : route('employee.bookings.my') }}"
                        class="px-3 py-1.5 text-xs rounded-lg border hover:bg-gray-100 transition">
                        {{ app()->getLocale() == 'ar' ? '← العودة للقائمة' : '← Back to list' }}
                    </a>

                    @php
                    $isOwner = $booking->employee_id === auth()->id();
                    $status = $booking->status;
                    @endphp

                    @if($isOwner)
                    @if($status === 'pending')
                    <form method="POST" action="{{ route('employee.bookings.approve', $booking->id) }}">
                        @csrf @method('PATCH')
                        <button class="px-3 py-1 rounded-full text-sm bg-green-50 border border-green-200 text-green-700 hover:bg-green-100">
                            {{ __('messages.booking.approve') }}
                        </button>
                    </form>

                    <button type="button" onclick="openRejectModal({{ $booking->id }})"
                        class="px-3 py-1 rounded-full text-sm bg-red-50 border border-red-200 text-red-700 hover:bg-red-100">
                        {{ __('messages.booking.reject') }}
                    </button>

                    <form method="POST" action="{{ route('employee.bookings.cancel', $booking->id) }}">
                        @csrf @method('PATCH')
                        <button class="px-3 py-1 rounded-full text-sm bg-gray-100 border text-gray-700 hover:bg-gray-200">
                            {{ __('messages.booking.cancel') }}
                        </button>
                    </form>
                    @elseif($status === 'approved')
                    <a href="{{ route('employee.reschedule.form', $booking->id) }}"
                        class="px-3 py-1 rounded-full text-sm bg-blue-50 border border-blue-200 text-blue-700 hover:bg-blue-100">
                        {{ __('messages.booking.reschedule') }}
                    </a>
                    <form method="POST" action="{{ route('employee.bookings.complete', $booking->id) }}">
                        @csrf @method('PATCH')
                        <button class="px-3 py-1 rounded-full text-sm bg-emerald-50 border border-emerald-200 text-emerald-700 hover:bg-emerald-100">
                            {{ __('messages.booking.complete') }}
                        </button>
                    </form>
                    @endif
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Column: Chat System --}}
        @if(auth()->user()->hasRole('employee'))
        <div class="lg:col-span-1">
            @include('dashboard.bookings._chat')
        </div>
        @endif

    </div>
</div>

@endsection
