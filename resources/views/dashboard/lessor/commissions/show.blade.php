@extends('dashboard.layout')

@section('content')

<div class="max-w-2xl mx-auto p-6 text-black">

    {{-- Title --}}
    <div class="mb-6">
        <h2 class="text-2xl font-semibold tracking-tight">
            {{ app()->getLocale() === 'ar' ? 'تفاصيل العمولة' : 'Commission Details' }}
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            {{ app()->getLocale() === 'ar'
                ? 'قم برفع إثبات الدفع لتأكيد العمولة'
                : 'Upload payment proof to verify commission' }}
        </p>
    </div>

    {{-- Info Card --}}
    <div class="border border-black/10 rounded-xl p-5 bg-white mb-5 shadow-sm">

        <div class="grid grid-cols-1 gap-3 text-sm">

            <div class="flex justify-between">
                <span class="text-gray-500">
                    {{ app()->getLocale() === 'ar' ? 'رقم الحجز' : 'Booking ID' }}
                </span>
                <span class="font-medium">#{{ $commission->booking_id }}</span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-500">
                    {{ app()->getLocale() === 'ar' ? 'المبلغ' : 'Amount' }}
                </span>
                <span class="font-medium">
                    {{ $commission->amount }} {{ $commission->currency }}
                </span>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-500">
                    {{ app()->getLocale() === 'ar' ? 'الحالة' : 'Status' }}
                </span>
                <span class="font-semibold uppercase tracking-wide">
                    {{ $commission->status }}
                </span>
            </div>

        </div>
    </div>

    {{-- Instruction --}}
    <div class="border border-black/10 rounded-xl p-5 bg-gray-50 mb-5">

        <p class="text-sm text-gray-700 leading-relaxed">
            {{ app()->getLocale() === 'ar'
                ? 'يجب رفع إثبات الدفع ليتم التحقق من العمولة. سيقوم الموظف بمراجعة الطلب وتأكيده.'
                : 'You must upload payment proof to verify the commission. The employee will review and confirm it.' }}
        </p>

    </div>

    {{-- Upload Form --}}
    @if($commission->status !== 'paid')

    <form method="POST"
          action="{{ route('lessor.lessor.commission.pay', $commission->id) }}"
          enctype="multipart/form-data"
          class="border border-black/10 rounded-xl p-5 bg-white space-y-4 shadow-sm">

        @csrf

        <div>
            <label class="text-xs text-gray-500">
                {{ app()->getLocale() === 'ar' ? 'مرجع الدفع' : 'Payment Reference (optional)' }}
            </label>

            <input type="text"
                   name="payment_reference"
                   class="w-full mt-1 border border-black/20 rounded-lg px-3 py-2 focus:outline-none focus:ring-1 focus:ring-black">
        </div>

        <div>
            <label class="text-xs text-gray-500">
                {{ app()->getLocale() === 'ar' ? 'إثبات الدفع' : 'Upload Proof' }}
            </label>

            <input type="file"
                   name="payment_image"
                   required
                   class="w-full mt-1 border border-black/20 rounded-lg px-3 py-2 bg-white">
        </div>

        <button class="w-full bg-black text-white py-2 rounded-lg hover:bg-gray-900 transition">
            {{ app()->getLocale() === 'ar' ? 'رفع الإثبات' : 'Upload Proof' }}
        </button>

    </form>

    @else

    <div class="border border-black/10 rounded-xl p-5 bg-white text-center">

        {{-- Check Icon --}}
        <div class="flex justify-center items-center mb-3 bg-green-100 rounded-full w-16 h-16 mx-auto">
            <svg class="w-12 h-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <div class="text-lg font-bold text-green-600">
            {{ app()->getLocale() === 'ar' ? 'تم التأكيد' : 'Payment Verified' }}
        </div>

        <div class="text-xs text-gray-500 mt-1">
            {{ app()->getLocale() === 'ar'
                ? 'تمت الموافقة على العمولة من قبل الموظف'
                : 'This commission has been approved by the employee' }}
        </div>

    </div>

    @endif

</div>

@endsection
