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
            <label class="text-xs text-gray-500 block mb-2">
                {{ app()->getLocale() === 'ar' ? 'إثبات الدفع' : 'Upload Proof' }}
            </label>

            <label for="payment_image"
                id="uploadBox"
                class="flex flex-col items-center justify-center w-full h-56 border-2 border-dashed border-black/20 rounded-2xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition group">

                <div id="uploadContent"
                    class="flex flex-col items-center justify-center text-center px-4">

                    {{-- Upload Icon --}}
                    <svg id="uploadIcon"
                        class="w-12 h-12 text-gray-400 mb-3 group-hover:text-black transition"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>

                    <p id="uploadText"
                        class="text-sm font-medium text-gray-700">
                        {{ app()->getLocale() === 'ar'
                    ? 'اضغط لرفع الصورة'
                    : 'Click to upload image' }}
                    </p>

                    <p id="fileName"
                        class="text-xs text-gray-400 mt-2 hidden">
                    </p>

                </div>

                <input id="payment_image"
                    type="file"
                    name="payment_image"
                    accept="image/*"
                    required
                    class="hidden">
            </label>
        </div>



        <button class="w-full bg-black text-white py-2 rounded-lg hover:bg-gray-900 transition">
            {{ app()->getLocale() === 'ar' ? 'رفع الإثبات' : 'Upload Proof' }}
        </button>

    </form>

    @else



    <div class="border border-black/10 rounded-2xl p-6 bg-white text-center shadow-sm">

        {{-- Success Icon --}}
        <div class="flex justify-center items-center mb-4 bg-green-100 rounded-full w-16 h-16 mx-auto">
            <svg class="w-10 h-10 text-green-500"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">

                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 13l4 4L19 7" />
            </svg>
        </div>

        {{-- Title --}}
        <div class="text-xl font-bold text-green-600">
            {{ app()->getLocale() === 'ar'
            ? 'تم تأكيد العمولة'
            : 'Commission Approved' }}
        </div>

        {{-- Description --}}
        <div class="text-sm text-gray-500 mt-2 leading-relaxed">
            {{ app()->getLocale() === 'ar'
            ? 'تمت مراجعة إثبات الدفع والموافقة على العمولة بنجاح.'
            : 'The payment proof has been reviewed and approved successfully.' }}
        </div>

        {{-- PDF Button --}}
        @if($commission->receipt_pdf)

        <a href="{{ asset('storage/'.$commission->receipt_pdf) }}"
            target="_blank"
            class="mt-6 inline-flex items-center justify-center gap-2 w-full bg-black text-white py-3 rounded-xl hover:bg-gray-900 transition duration-200">

            {{-- PDF Icon --}}
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">

                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2h-5.586a1 1 0 01-.707-.293l-1.414-1.414A1 1 0 0010.586 2H5a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>

            <span>
                {{ app()->getLocale() === 'ar'
                ? 'تحميل إيصال الـ PDF'
                : 'Download PDF Receipt' }}
            </span>

        </a>

        @endif

    </div>



    @endif

</div>
<script>
    const input = document.getElementById('payment_image');
    const uploadBox = document.getElementById('uploadBox');
    const uploadText = document.getElementById('uploadText');
    const fileName = document.getElementById('fileName');
    const uploadIcon = document.getElementById('uploadIcon');

    input.addEventListener('change', function() {

        if (this.files && this.files[0]) {

            uploadBox.classList.remove('bg-gray-50');
            uploadBox.classList.add('bg-green-50', 'border-green-400');

            uploadText.innerText = "{{ app()->getLocale() === 'ar' ?
                'تم اختيار الصورة بنجاح' :
                'Image selected successfully'
        }
    }
    ";

    uploadText.classList.remove('text-gray-700'); uploadText.classList.add('text-green-600');

    uploadIcon.classList.remove('text-gray-400'); uploadIcon.classList.add('text-green-500');

    fileName.innerText = this.files[0].name; fileName.classList.remove('hidden');
    }
    });
</script>
@endsection
